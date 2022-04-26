<?php

session_start();

if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
	header("Location: http://www.info.campusstore.utah.edu/inventory/index.php");
	exit();
}

$sku = substr($_POST['submit'], 0, strpos($_POST['submit'], '-'));
$string = substr($_POST['submit'], strpos($_POST['submit'], '-')+1); //fixnum-shelfnum-boxnum. really jimmy?

$tmp = $_SESSION['barcode'];

for($i = 0; $i < count($tmp[$sku]); $i++){
	if(strpos($tmp[$sku][$i], $string) !== false){ //if this is the one for the right location:
		$qty = $_POST['newQuantity'];
		
		if($qty == 0){
			//this is so buggy. fix this man. (? okay jimmy? but what's the bug? help me out here??)
			array_splice($tmp[$sku], $i, 1);
			if(count($tmp[$sku]) == 0){
				unset($tmp[$sku]);
			}		
			break;
		}
		
		$tmp[$sku][$i] = $string.$qty;
	}
}

$_SESSION['barcode'] = $tmp;

header('Location: scan.php');

?>
