<?php
session_set_cookie_params(43200); //12 hours
session_start();
include 'classes/session-timeout.php';

$incorrect = false;

if(isset($_SESSION['verified'])){
	if($_SESSION["verified"] == "v3r1f13d" || $_SESSION['verified'] == "v3r1f13d-adm1n"){
		if(isset($_GET['admin'])){  
			header("Location: /inventory/admin/adminPanel.php");
			die();
		}
		if(isset($_GET['apple'])){
			header("Location: /inventory/apple/userPanel.php");
			die();
		}
		else{
			//echo "Top Redirect";
			header("Location: /inventory/userPanel.php");
			die();
		}
	}
}

if(isset($_POST["username"]) && isset($_POST["password"])){
	include_once("classes/db.php");


	$query = "SELECT 1 FROM users WHERE username = '".$_POST['username']."';";
	$exists = $conn->query($query)->num_rows;
	if(!$exists){
		$incorrect = true;
	}

	$query = "SELECT (clearance) FROM users WHERE username = '".$_POST["username"]."';";
	$clearance = $conn -> query($query)->fetch_assoc()['clearance'];

	$query = "SELECT (password) FROM users WHERE username = '".$_POST["username"]."';";
	$hashedPassword = $conn -> query($query)->fetch_assoc()['password'];

	if(sha1($_POST["password"]) == $hashedPassword){	
		//verify captcha, set the appropriate session cookies
		$_SESSION['g-recaptcha-response'] = $_POST['g-recaptcha-response'];
		$_SESSION['username'] = $_POST["username"];

		$ip = $_SERVER['REMOTE_ADDR'];

		// your secret key
		$secretKey = "RECAPTCHA_SECRET_KEY";
		if($ip=="127.0.0.1")
			$secretKey = "6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe";

		$captcha=$_SESSION['g-recaptcha-response'];

		if(!$captcha){
			echo '<h2>Please go back and submit check the box that says "I\'m not a robot."</h2>';
			echo "<button type=\"back\" id=\"submit\" onclick=\"history.go(-1);\">Go Back</button>";
			exit;
		}
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
		$responseKeys = json_decode($response,true);
		if($responseKeys['success']==false){
			echo '<h2>There was an error with your form submission: reCAPTCHA error. Please try again.</h2>';
			echo "<button type=\"back\" id=\"submit\" onclick=\"history.go(-1);\">Go Back</button>";

		} else {
			echo "Success";

			//update login time
			$date = date('Y-m-d h:i:s', time());
			$query = "UPDATE users SET last_login = '".$date."' WHERE username = '".$_POST["username"]."';";
			$conn -> query($query);

			$query = "SELECT (temp_password) FROM users WHERE username = '".$_POST["username"]."';";
			$result = $conn -> query($query)->fetch_assoc();

			mysqli_close($conn);

			if($result['temp_password'] == '1'){
				$_SESSION["verified"] = "almost-v3r1f13d";
				header("Location: /inventory/resetPassword.php");
				exit(0);
			}else{
				if($clearance>0){
					$_SESSION["verified"] = "v3r1f13d-adm1n";
				}
				else{
					$_SESSION["verified"] = "v3r1f13d";
				}
				if(isset($_GET['admin'])){
					header("Location: /inventory/admin/adminPanel.php");
					die();
				}
				else if(isset($_GET['apple'])){
					header("Location: /inventory/apple/userPanel.php");
					die();
				}
				else{
					//echo "Bottom Redirect";
					header("Location: /inventory/userPanel.php");
					die();
				}

			}
		}

	}else{
		$incorrect = true;
	}
}
$recaptchaKey = ($_SERVER['REMOTE_ADDR']=="127.0.0.1") ? "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" : "RECAPTCHA_SITE_KEY";
?>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>

		<title>Inventory - Sign In</title>
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
	  <link rel="shortcut icon" href="favicon.ico" />

	</head>

	<body>
		<div id="title" class="contain">
			<span class="textcenter" id="titleText">
				<h1><strong>Inventory</strong> - Sign In</h1>
			</span>
		</div>
		<div class="contain">
			<img src="images/UCSLogos.png" alt="University Campus Store">	
		</div>
<?php
if($incorrect){
	echo "<div class=\"contain\"><p style=\"color: red; font-size: 17px;\">The username or password entered is incorrect.</p></div>";
}
?>
<div class="contain">
	<form action="/inventory/<?php if(count($_GET)>0) echo "?".array_keys($_GET)[0];?>" method="post">
		<div class="center"><label for="username">Username: </label><input type="text" id="username" name="username" required><br></div>
		<div class="center"><label for="password">Password: </label><input type="password" id="password" name="password" required><br></div>
		<div class="subForm">
			<div class="g-recaptcha" data-sitekey="<?php echo $recaptchaKey; ?>" style="margin-left: auto; margin-right: auto; display: block; width: 50%;"></div>
			<span>
				<div class="centerButton"><button type="submit" name="submit" value="Submit">Submit</button></div>
			</span>
		</div>	
	</form>
</div>
<div class="center"><a style="text-decoration: none;" href="request-account.php"><p style="text-align: center; color: #cc0000;">Request an account</p></a></div>

	</body>
</html>
