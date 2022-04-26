<?php
$admin = true;
include '../classes/phpheader.php';

$unid = $_POST['unid'];
$query = "SELECT 1 FROM users WHERE username='$unid'";
if(mysqli_num_rows(mysqli_query($conn,$query))>0){
	//Account already exists
	$query = "UPDATE users
		SET password = '".sha1($_POST['password'])."',
		clearance = 0,
		temp_password = 1
		WHERE username='$unid'";
	$newAccount = false;
}

else{
	$query = "SELECT (id) FROM users WHERE username ='".$_SESSION['username']."';";

	$result = $conn->query($query)->fetch_assoc();
	$manager_id = $result['id'];

	$query = "INSERT INTO users (username, password, name, manager_id) VALUES ('" . $_POST["unid"]. "','" . sha1($_POST["password"]) . "','".$_POST['firstname']." ".$_POST['lastname']."','".$manager_id."');";

	$newAccount = true;
}

if($conn->query($query) === false){
	echo "<h1>Error: User not properly updated. Please contact the IT department.</h1>";
	exit();
}

$head = ['title' => 'Added User'];
$header = "User added successfully!";
include '../classes/header.php';
?>
<div class="contain">
	<h1>
		<?php if($newAccount){ ?>User added successfully! <?php }else{ ?>
		User account updated with temp password. <?php } ?>
	</h1>
	<br><br>
	<a href="adminPanel.php"><p style="text-align: center">Back to Admin Panel</p></a>
</div>

<?php include '../classes/footer.php'; ?>
