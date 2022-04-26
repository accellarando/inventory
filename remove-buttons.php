<?php
require('classes/phpheader.php');

//Modify quantity
$itemSku = $_POST['sku'];
$originalItem = $_SESSION['items'][$itemSku];
$originalQty = $originalItem['QTY'];
$qty=$originalQty;
if(isset($_POST['remove']))
	$qty--;
if(isset($_POST['update'])){
	strlen($_POST['newQuantity'])>0 ? $qty = $_POST['newQuantity'] : $qty = $qty; 
	//echo "<pre>";strlen($_POST['newQuantity']); echo "</pre>";
	//die();
}
$delta = $qty-$originalQty;

//change the $items to display back on main
$_SESSION['items'][$itemSku]['QTY']=$qty;

//add the change to $changes
$_SESSION['changes'][$itemSku] = $delta;

//go back to the removing page
header("Location: remove.php");
?>
