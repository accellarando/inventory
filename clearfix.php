<?php
if($_POST["submit"] != "okay, really clear fixture"){
	echo "<h1>403 -- Forbidden</h1>";
	die();
}

include_once("classes/db.php");

$query = "DELETE FROM scanned 
	WHERE fixtureNum = '".$_POST['fixNum']."' AND
	store = '".$_POST['storenum']."' AND
	dept = '".$_POST['dept']."';";

if($conn->query($query) === TRUE){
	$query = "DELETE FROM completed_fixtures 
		WHERE fixture = '".$_POST['fixNum']."' AND
		store = '".$_POST['storenum']."' AND
		department = '".$_POST['dept']."';";
	$conn->query($query);

	$head = ['title' => 'Fixture cleared'];
	$header = 'The fixture has been cleared!';
	include 'classes/header.php';
}
else{
	echo "<h1>Error: Unable to clear database. Please contact the IT department.</h1>";
}
include 'classes/footer.php';
?>
