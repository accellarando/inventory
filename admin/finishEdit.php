<?php
$admin = true;
include '../classes/phpheader.php';

$idArray = explode(",",$_POST['ids']);
$usernameArray = explode(",",$_POST['usernames']);
$nameArray = explode(",",$_POST['names']);
$clearanceArray = explode(",",$_POST['clearances']);

if(count(array_unique($usernameArray)) != count($usernameArray)){
	echo "<h2>Go back and check that each UNID entered is unique.</h2>";
	echo "<button type='back' id='submit' onclick='history.go(-1);'>Go Back</button>";
	exit();
}

for($i=1;$i<count($idArray);$i++){
	$id = $idArray[$i];
	$username = $usernameArray[$i];
	$name = $nameArray[$i];
	$clearance = $clearanceArray[$i];

	$previousUsername = mysqli_fetch_assoc(mysqli_query($conn, "SELECT username FROM users WHERE id=$id"));

	if($username!=$previousUsername){
		$checkUnique = mysqli_num_rows(mysqli_query($conn, "SELECT 1 FROM users WHERE username='$username' AND id!=$id"));
		if($checkUnique>0){
			echo "<h2>Go back and check that each UNID entered is unique.</h2>";
			echo "<button type='back' id='submit' onclick='history.go(-1);'>Go Back</button>";
			exit();
		}
	}

	$query = " UPDATE users
		SET username = '$username',
		name = '$name',
		clearance = '$clearance'
		WHERE id = '$id'";

	$success = mysqli_query($conn,$query);
}

$head = ['title'=>'Update Users'];
$header = "";
include '../classes/header.php';

?>
		<div class="contain">
			<?php if($success){?>
			<h2>User(s) edited successfully.</h2>
			<?php }else{ ?>
			<h2>ERROR:</h2>
			<br>
			<?php echo mysqli_error($conn); }?>
		</div>
<?php include '../classes/footer.php'; ?>
