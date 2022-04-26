<?php
$admin = true;
include '../classes/phpheader.php';
	
	$incorrect = false;
	$updated = false;
	
	if(isset($_POST['password'])){
		if($_POST["password"] == $_POST["reppassword"]){
			if(!$conn){
				//error messages
				echo "Unable to connect to database! <br>";
				echo "errno - " . mysqli_connect_errno()."<br>";
				echo "error - " . mysqli_connect_error()."<br>";
				die();
			}
			
			$query = "UPDATE users SET password = '".sha1($_POST["password"])."' WHERE username = '".$_SESSION['username']."';";
			$conn -> query($query);
			
			$query = "UPDATE users SET temp_password = '0' WHERE username = '".$_SESSION['username']."';";
			$conn -> query($query);
			
			$_SESSION["verified"] = "v3r1f13d-adm1n";
			
			header("Location: /inventory/admin/adminPanel.php");
			exit(0);
		}else{
			$incorrect = true;
		}
	}
$head = ['title'=>'Reset Password'];
$header = "<strong>Reset</strong> Password";
include '../classes/header.php';

if($incorrect){
	echo "<div class=\"contain\"><p style=\"color: red; font-size: 17px;\">The passwords do not match.</p></div>";
}
if($updated){
	echo "<div class=\"contain\"><p style=\"color: green; font-size: 17px;\">The password has been updated.</div>";
}
?>
<div class="contain">
	<form action="resetPassword.php" method="post" enctype="multipart/form-data">
		<div style="width: 60%; margin: 0 auto;"><label for="password">New Password: </label><input type="password" name="password" required><br></div><br>
		<div style="width: 60%; margin: 0 auto;"><label for="reppassword">Repeat Password: </label><input type="password" name="reppassword" required><br></div>
		<div class="subForm">
			<div class="centerButton"><button type="submit" name="submit" value="Submit">Submit</button></div>
		</div>
	</form>
</div>
<?php include '../classes/footer.php'; ?>
