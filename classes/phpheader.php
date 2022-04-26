<?php
//error_log(__DIR__."/helpers.php");
//require_once('/info/inventory/classes/helpers.php');
require_once __DIR__."/helpers.php";

if(isset($admin)){
	if($_SESSION["verified"] != "v3r1f13d-adm1n"){
		header("Location:/inventory/admin/index.php");
		exit();
	}
}

if(isset($apple)){
	if($_SESSION["verified"] != "v3r1f13d"){
		header("Location:/inventory/apple/index.php");
		exit();
	}
}

if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
	header("Location: /inventory/index.php");
	exit();
}
?>

