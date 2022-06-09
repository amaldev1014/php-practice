<?php 

// http://localhost/listings

$db = new PDO('sqlite:files/listings.db3');

$cat_id = 'a244';


$sql = "SELECT `rowid`,`name` FROM lookup_couriers";
$courier_lookup = $db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);

 // echo '<pre style="background:#002; color:#fff;">'; print_r($courier_lookup[6]); echo '</pre>'; die();


// Join columns from Listings and Listings Ebays
$sql = "SELECT
        listings.product_name,
        listings.packaging_band,
        
        listings_couriers.courier,
        
        listings.lowest_variation_weight,
        listings.variation,
        
        listings_ebay.prev_price,
        listings_ebay.new_price,
        listings_ebay.perc_advertising,
        
        comps_ids.comp1,
        comps_ids.comp2,
        comps_ids.comp3
        
        FROM listings
        
        INNER JOIN listings_ebay
        ON listings.id_lkup = listings_ebay.id 
        
        INNER JOIN comps_ids
        ON listings_ebay.id = comps_ids.id
         
        INNER JOIN  listings_couriers
        ON comps_ids.id = listings_couriers.id
        
        WHERE listings.cat_id = '$cat_id' AND listings.remove IS NULL";

// Need to add 'comps_ids' table values. (comp1 comp2 comp3)

// Need to add 'listing_couriers' table values. To get names use 'lookup_couriers'



        
// fetching data from database
$result = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

 // echo '<pre style="background:#002; color:#fff;">'; print_r($result); echo '</pre>'; die();


// Initializing column names 
$col_names = [
    'Product Name',
    'Packaging Brand',
    'Couriers',
    'Lowest Variation Weight',
    'Variation',
    'prev_price',
    'new_price',
    'perc_advertising',
    'COMP1',
    'COMP2',
    'SPON COMP'
];

/**
 * Function loops through the contents and creates table
 * 
 * @param Column names the title of each column as an array
 * @param Content Data fetched from database
 * @return 
 */
function make_table($col_names, $content, $courier_lookup)
{
    $table = [];
    $table[] = '<table>';
    $table[] = '<tr><th>' . implode('</th><th>', $col_names) . '</th></tr>';

    foreach ($content as $rec) {
        
        // echo '<pre style="background:#002; color:#fff;">'; print_r($rec); echo '</pre>'; die();
        
        /*
        $rec = [
            [product_name] => Brown Rock Salt x 5kg bag
            [packaging_band] => 5
            [lowest_variation_weight] => 1
            [variation] => 5
            
        ]
        */
        
        $table[] = '<tr>';
        
        foreach ($rec as $key => $val) {
            $table[] = 'courier' == $key ? "<td>$courier_lookup[$val]</td>" : "<td>$val</td>";
        }
        
        $table[] = '</tr>';
    }

    $table[] = '</table>';

    return implode('', $table);
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>listings</title>


<style>
    
    table{
        border-collapse: collapse;
        width: 100%;
        border: 1px solid black;
    }
    th{
        background: #5b9bd5;
    }
    td{
        padding: 2px;
        border :1px solid black;
    }
    
    tr:nth-child(2n+2){ background: rgb(228, 238, 250); } /* light blue */
</style>

</head>
<body>

 <?= make_table($col_names, $result,$courier_lookup) ?>

</body>
</html>