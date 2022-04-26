<?php
session_start();
include 'classes/session-timeout.php';

$incorrect = false;

if($_SESSION["verified"] != "almost-v3r1f13d"){
	header("Location: http://www.info.campusstore.utah.edu/inventory/index.php");
}

if(isset($_POST['password'])){
	if($_POST["password"] == $_POST["reppassword"]){
		include_once("classes/db.php");
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

		$query = "SELECT (clearance) FROM users WHERE username = '".$_POST["username"]."';";
		$clearance = $conn -> query($query)->fetch_assoc()['clearance'];
		if($clearance>0)
			$_SESSION["verified"] = "v3r1f13d-adm1n";
		else
			$_SESSION["verified"] = "v3r1f13d";

		header("Location: /inventory/userPanel.php");
		exit(0);
	}else{
		$incorrect = true;
	}
}
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

		<title>Reset Password</title>
		<!--<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7/themes/smoothness/jquery-ui.css">
	  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->

	  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
	  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

	  <link rel="stylesheet" type="text/css" media="all" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css">
	  <!--jquery.timepicker.min.css-->

	  <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900" rel="stylesheet" type="text/css">
	  <link rel="stylesheet" type="text/css" href="style.css">

	</head>

	<body>
		<div id="title" class="contain">
			<span class="textcenter" id="titleText">
				<h1><strong>Reset</strong> Password</h1>
			</span>
		</div>
		<div class="contain">
			<img src="images/UCSLogos.png" alt="University Campus Store">	
		</div>
<?php
if($incorrect){
	echo "<div class=\"contain\"><p style=\"color: red; font-size: 17px;\">The passwords do not match.</p></div>";
}
?>
<div class="contain">
	<form action="resetPassword.php" method="post" enctype="multipart/form-data">
		<div style="width: 50%; margin: 0 auto;"><label for="password">New Password: </label><input type="password" name="password" required><br></div><br>
		<div style="width: 50%; margin: 0 auto;"><label for="reppassword">Repeat Password: </label><input type="password" name="reppassword" required><br></div>
		<div class="subForm">
			<div class="centerButton"><button type="submit" name="submit" value="Submit">Submit</button></div>
		</div>
	</form>
</div>

<footer>
	<hr>
	<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
</footer>
	</body>
</html>
