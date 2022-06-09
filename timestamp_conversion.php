<?php 
// http://localhost/dates/timestamp_conversion.php


$db = new PDO('sqlite:cache_copy.db3');


$orderIDs_to_delete = timestampToDateConversion_fnc('2018-08-08',$db);
$orderIDs_to_delete_str = implode("','", $orderIDs_to_delete);

$sql = "DELETE FROM `orders` WHERE `orderID` IN ('$orderIDs_to_delete_str')";
$db->query($sql);



/**
 * This function ideally returns an array which will have all customers with lastmodified date after the specified date
 * @param Specified date
 * @param datebase object
 * @return an array of orderIDs
 */
function timestampToDateConversion_fnc($specified_date, $db)
{
    $out = [];
    $sql = "SELECT `orderID`, `lastModified` FROM `orders`";
    $results = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    // echo '<pre style="background:#002; color:#fff;">'; print_r($results); echo '</pre>'; die();

    // Loop $results and return records where 'lastModified' timestamp is less than supplied date.
    foreach ($results as $rec) {
        //  a function (check_timestamp) to check timestamp against date.
        if (check_timestamp($rec['lastModified'], $specified_date)) {
           $out[] = $rec['orderID'];
        }
    }

    return $out;
}

//  a function (check_timestamp) to check timestamp against date.
function check_timestamp($lastModified, $specified_date)
{
    // Convert timestamp to date
    $lastModified_date = date('Y-m-d',$lastModified);

    return $lastModified_date < $specified_date ? true : false;
}
