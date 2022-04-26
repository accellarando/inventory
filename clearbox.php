<?php
if($_POST["submit"] != "okay, really clear box"){
	echo "<h1>403 -- Forbidden</h1>";
	die();
}

include_once("classes/db.php");

$query = "DELETE FROM scanned WHERE 
	fixtureNum = '".$_POST['fixNum']."' AND 
	boxNum = '".$_POST['boxNum']."' AND
	dept = '".$_POST['dept']."' AND
	store = '".$_POST['storenum']."';";

if($conn->query($query) === TRUE){
	//echo out a prompt to the user if successful
	$query = "DELETE FROM completed_fixtures 
		WHERE fixture = '".$_POST['fixNum']."' AND
		store = '".$_POST['storenum']."' AND
		department = '".$_POST['dept']."';";
	$conn->query($query);

	$head = ['title' => 'Box cleared'];
	$header = 'The box has been cleared!';
	include 'classes/header.php';
}
else{
	echo "<h1>Error: Unable to clear database. Please contact the IT department.</h1>";
}
include 'classes/footer.php';
?>
