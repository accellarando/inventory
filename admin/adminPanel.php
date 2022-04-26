<?php
$admin = true;
$head = ['title'=>'Admin Panel'];
$header = "<strong>Admin</strong> Panel";
include '../classes/header.php';

$query = "SELECT clearance FROM users WHERE username ='".$_SESSION['username']."';";

$clearance = $conn->query($query)->fetch_assoc()['clearance'];
?>
<div class="contain" style="height: 200px;">
	<div style="float: left; margin-left: 100px;">
		<a href="add.php"><p style="text-align: center;">Add a User</p></a>
		<a href="remove.php"><p style="text-align: center;">Remove a User</p></a>
		<a href="allUsers.php"><p style="text-align: center;">View All Users</p></a>
		<a href="resetUserPassword.php"><p style="text-align: center;">Reset User Password</p></a>
<?php
if($clearance == 2){
	echo "<a href=\"add-manager.php\"><p style=\"text-align: center;\">Add a Manager</p></a>";
}
?>
	</div>
	<div style="float: right; margin-right: 100px;">
<?php
if($clearance == 2){
	echo "<a href=\"editUsers.php\"><p style=\"text-align: center;\">Edit Users</p></a>";
}
?>
<a href="expiringreport.php"><p style="text-align: center;">Expiring User report</p></a>
<a href="importStoreFile.php"><p style="text-align: center;">Import Validation File</p></a>
<a href="clearScannedSpecific.php"><p style="text-align: center;">Clear Data by Dept/Store</p></a>
<a href="clearall.php"><p style="text-align: center;">Clear all Scanned Data</p></a>
	</div>
</div>
<?php include '../classes/footer.php';?>
