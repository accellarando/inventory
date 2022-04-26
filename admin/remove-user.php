<?php
$admin = true;
include '../classes/phpheader.php';

$query = "SELECT id, clearance FROM users WHERE username='".$_SESSION['username']."';";

$result = $conn->query($query)->fetch_assoc();

//checks admin credentials to see if they are allowed to delete user
if((int)$result['clearance'] < 2){
	$query = "SELECT manager_id FROM users WHERE username='".$_POST['unid']."';";

	$manager = $conn->query($query)->fetch_assoc()['manager_id'];

	if($result['id'] != $manager){
		die("<h1>You do not have clearance to delete that user.</h1>");
	}
}

$query = "DELETE FROM users WHERE username ='" . $_POST["unid"]. "';";

if($conn->query($query) === TRUE){
	//echo out a prompt to the user if successful
	$head = ['title'=>'Removed User'];
	$header = "User removed successfully!";
	include '../classes/header.php';
?>
<div class="contain">
	<p style="font-size: 18px; text-align:center;">The user has been successfully removed. 
	<br><br></div>
	<?php 
	include '../classes/footer.php';
}
	else{
echo "<h1>Error: User not properly removed from the database. Please contact the IT department.</h1>";
}

$conn->close();
?>
