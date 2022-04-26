<?php
session_start();

if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
	header("Location: http://www.info.campusstore.utah.edu/inventory/index.php");
	exit();
}

$tmp = $_SESSION['barcode'];

$sku = substr($_POST['identifier'],0,strpos($_POST['identifier'], '-'));
$string = substr($_POST['identifier'],strpos($_POST['identifier'], '-')+1);

for($i = 0; $i < count($tmp[$sku]); $i++){
	if(strpos($tmp[$sku][$i], $string) !== false){
		$qty = (int)substr($tmp[$sku][$i], strrpos($tmp[$sku][$i], '-')+1);
		if(isset($_POST['remove']))
			$qty--;
		if(isset($_POST['update']))
			$qty = $_POST['newQuantity'];

		if($qty <= 0){
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
