<?php
/*
http://localhost/get_hermes_data/get_hermes_data.php

DESCRIPTION:

➤ Post list of order IDs from a text input form field (space separated).
➤ Replace spaces with single quoted commas and assign to $orderIDs_str variable.
➤ Select all records from meta@meta.db3 (FESP-REFACTOR/) WHERE `orderID` IN ('$orderIDs_str').
➤ Assign tracking_id values to $trackingIDs array (*foreach loop and *json_decode() function). key: orderID  / value: tracking_id
➤ Select all records (`orderId`,`title` fields) from amazon_items|ebay_items|ebay_prosalt_items|floorworld_items|onbuy_items|website_items@api_orders.db3
  (FESP-REFACTOR/FespMVC/NEW_API_SYSTEM/) WHERE `orderId` IN ('$orderIDs_str').  key: orderId  / value: title

➤ Convert $trackingIDs array to string ($trackingIDs_str), values only - "\n" separated (*array_values() / *implode() functions).
➤ Repeat for $titles.
➤ Display $trackingIDs_str and $titles_str in their own textareas.


Working example: http://192.168.0.24/FESP-REFACTOR/get_hermes_data.php

*/


/*
$orderIDs_str = "1161792','206-1275537-8544337','204-3928442-3629913','1162557','1162607";

$db_meta = new PDO('sqlite:meta.db3');
$sql = "SELECT * FROM `meta` WHERE `orderID` IN ('$orderIDs_str')";
$meta_arr = $db_meta->query($sql)->fetchAll(PDO::FETCH_ASSOC); // FETCH_ASSOC, FETCH_COLUMN, FETCH_NUM, FETCH_KEY_PAIR


echo '<pre>';
print_r($meta_arr);
echo '</pre>';

die();
*/


$trackingIds = '';
$titles = '';
if( isset($_POST['form_data']) && '' != $_POST['order_ids'] ){
	
	//echo "<pre>"; print_r($_POST); echo "</pre>";

	$orderIDs_str = $_POST['order_ids'];

	 // $orderIDs_str = "1161792 206-1275537-8544337 204-3928442-3629913 1162557 1162607";

	/*
	$orderIDs_arr = explode(' ', $orderIDs_str);
	echo "<pre>"; print_r( $orderIDs_arr ); echo "</pre>";
	$orderIDs_str =implode("\n", $orderIDs_arr);
	print_r( $orderIDs_str ); die();
	echo "<pre>"; print_r( $orderIDs_str ); echo "</pre>"; die();
	*/


	$orderIDs_str = str_replace(' ', "','", $orderIDs_str);


	// 1161792','206-1275537-8544337','204-3928442-3629913','1162557','1162607

	$db_meta = new PDO('sqlite:meta.db3');
	$sql = "SELECT * FROM `meta` WHERE `orderID` IN ('$orderIDs_str')";
	$meta_arr = $db_meta->query($sql)->fetchAll(PDO::FETCH_ASSOC);

	$trackingIDs = [];
	foreach ($meta_arr as $val) {
		
		$info = json_decode($val['info'],true);

		// echo '<pre style="background:#002; color:#fff;">'; print_r($info); echo '</pre>';

		$trackingIDs[] = $info['tracking_id'];
	}

	// echo "<pre>"; print_r($trackingIDs); echo "</pre>";



	// Select all records (`orderId`,`title` fields) from amazon_items|ebay_items|ebay_prosalt_items|floorworld_items|onbuy_items|website_items@api_orders.db3
	//  (FESP-REFACTOR/FespMVC/NEW_API_SYSTEM/) WHERE `orderId` IN ('$orderIDs_str').  key: orderId  / value: title
	$api_orders = [];
	foreach (['amazon_items','ebay_items','ebay_prosalt_items','floorworld_items','onbuy_items'] as $platform) {
		$db_api_orders = new PDO('sqlite:api_orders.db3');
		$sql = "SELECT title FROM `$platform` WHERE `orderId` IN ('$orderIDs_str')";

		$tmp = $db_api_orders->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	  foreach ($tmp as $val) {
	  	$api_orders[] = $val['title'];
	  }
	}

     
	// echo "<pre>"; print_r($api_orders); echo "</pre>"; die();

	// $trackingIDs
	// $api_orders

	$trackingIds = implode("\n", $trackingIDs);

	$titles = implode("\n", $api_orders);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Find Hermes Tracking IDs and Titles</title>

<style>
	#ta1, #ta2{
		margin-top: 40px;
		height: 800px;
	}

	#ta1{
		width: 200px;
	}
	#ta2 
	{
		width: 900px;
	}
	#form_id
	{
		  position: absolute;
		  padding: 20;
		  border: :20;


		padding: 10px;
		border: 20px;
	}
</style>

</head>
<body>

<!-- Form -->
	<form method="post" id="fo">
		<input type="hidden" name="form_data" >

		<div id="form_id">
			
			<input type="text" name="order_ids" >

			<input type="submit" name="submit" value="Submit">
		</div>
			
		<!-- <textarea id="ta1"><?= $trackingIds ?></textarea> -->
		<textarea id="ta2"><?= $titles ?></textarea>
	</form>

<!-- <input type="text" name="order_ids"> -->

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
	$(function() {
		$('[name="order_ids"]').focus();


	// 	$('#ta1').css({"width", })
	});
</script>

</body>
</html>
