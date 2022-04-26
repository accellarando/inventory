<?php
$admin = true;
include '../classes/phpheader.php';
$newAccount = false;

$query = "SELECT (id) FROM users WHERE username ='".$_SESSION['username']."';";

$result = $conn->query($query)->fetch_assoc();
$manager_id = $result['id'];

$unid = $_POST['unid'];

$query = "SELECT * FROM users WHERE username='$unid'";
$result = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
if(count($result)>0){
	//This user already exists: just update and set a temp password
	$query = "UPDATE users
		SET password = '".sha1($_POST['password'])."',
		clearance = 1,
		temp_password = 1
		WHERE username='$unid'";
	if($conn->query($query) === false){
		echo "<h1>Error: User not properly updated. Please contact the IT department.</h1>";
		exit();
	}
}
else{
	//this user doesn't exist yet
	$query = "INSERT INTO users (username, password, name, clearance, manager_id) VALUES ('" . $_POST["unid"]. "','" . sha1($_POST["password"]) . "','".$_POST['firstname']." ".$_POST['lastname']."','1','".$manager_id."');";
	if($conn->query($query) === TRUE)
		$newAccount = true;
	else{
		echo "<h1>Error: User not properly added to the database. Please contact the IT department.</h1>";
		exit();
	}
}

$head = ['title' => 'Added Manager'];
$header = "Manager Added";
include '../classes/header.php';
?>
<div class="contain">
	<h1>
		<?php if($newAccount){ ?>Manager added successfully! <?php }else{ ?>
		Manager account updated with temp password. <?php } ?>
	</h1>

	<br><br>
	<a href="adminPanel.php"><p style="text-align: center">Back to Admin Panel</p></a>
</div>
<?php include '../classes/footer.php'; ?>
