<?php

session_start();

if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
	header("Location: http://www.info.campusstore.utah.edu/inventory/index.php");
	exit();
}

include_once("classes/db.php");

$sku = $_POST['submit'];
	
$query = "DELETE FROM bad_tags WHERE sku = '".$sku."';";
$conn->query($query);

header('Location: badtags.php');
exit();
?>
