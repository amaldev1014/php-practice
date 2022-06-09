<?php
/*
http://localhost/sandbox/get_hermes_data/get_hermes_data.php

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
*/


$orderIDs_str = "1161792','206-1275537-8544337','204-3928442-3629913','1162557','1162607','1155240','157851','1162060','203-6124733-2409123','026-9499362-1532365','026-5593936-3778735','FW-531','1163619','203-2240498-9365132','157534','1162320','203-6967281-2243525','157530','1163029','026-3386052-0801915','1162408','1159705','1163381','1162565','205-7942644-3717951','1163493','204-7842612-4648328','204-8629309-8604312','203-9679480-3089947','026-9311513-4421968','157770','1164592','204-8477542-8687501','158138";


$db = new PDO('sqlite:NEW_API_SYSTEM/api_orders.db3');
$db_api_orders = new PDO('sqlite:api_orders.db3');

foreach([
	// 'amazon_items',
	// 'ebay_items',
	// 'ebay_prosalt_items',
	// 'floorworld_items',
	// 'onbuy_items',
	'website_items',
	] as $platform_items ){

	$sql = "SELECT * FROM `$platform_items` WHERE `orderId` IN ('$orderIDs_str')";
	$platform_items_arr = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
	
	if( 'website_items' != $platform_items ){
		$stmt = $db_api_orders->prepare("INSERT INTO `$platform_items` VALUES (?,?,?,?,?,?,?,?)");
	}
	else{
		$stmt = $db_api_orders->prepare("INSERT INTO `$platform_items` VALUES (?,?,?,?,?,?,?,?,?)");
	}
	
	$db_api_orders->beginTransaction();
	foreach( $platform_items_arr as $rec ){
		if( 'website_items' != $platform_items ){
			$insert = [$rec['orderId'],$rec['itemId'],$rec['sku'],$rec['qty'],$rec['title'],$rec['variations'],$rec['price'],$rec['shipping']];
		}
		else{
			$insert = [$rec['orderId'],$rec['itemId'],$rec['sku'],$rec['qty'],$rec['title'],$rec['variations'],$rec['url'],$rec['price'],$rec['shipping']];
		}
		
		$stmt->execute($insert);
	}
	$db_api_orders->commit();
}

die();

if( isset($_POST['get_data']) ){
	// 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Find Hermes Tracking IDs and Titles</title>

<style></style>

</head>
<body>





</body>
</html>