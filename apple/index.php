<?php
session_start();
if($_SESSION["verified"] != "v3r1f13d-adm1n" && $_SESSION["verified"] != "v3r1f13d"){
	header("Location: ../?apple");
}
else
	header("Location: userPanel.php");
/*
	session_set_cookie_params(42300);
	session_start();
	include 'session-timeout.php';
	
	$incorrect = false;
	
	if($_SESSION["verified"] == "v3r1f13d" || $_SESSION['verified'] == "v3r1f13d-adm1n"){
		header("Location: /inventory/apple/userPanel.php");
	}
	
	if($_POST["username"] && $_POST["password"]){
		$conn
		if(!$conn){
			//error messages
			echo "Unable to connect to database! <br>";
			echo "errno - " . mysqli_connect_errno()."<br>";
			echo "error - " . mysqli_connect_error()."<br>";
			die();
		}

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
			//update login time
			$date = date('Y-m-d h:i:s', time());
			$query = "update users set last_login = '".$date."' WHERE username = '".$_POST["username"]."';";
			$conn -> query($query);
			
			$query = "SELECT (temp_password) FROM users WHERE username = '".$_POST["username"]."';";
			$result = $conn -> query($query)->fetch_assoc();
			
			mysqli_close($conn);
			
			$_SESSION['g-recaptcha-response'] = 1; //$_POST['g-recaptcha-response'];
			$_SESSION['username'] = $_POST["username"];
						
			if($result['temp_password'] == '1'){
				$_SESSION["verified"] = "almost-v3r1f13d";
				header("Location: /inventory/apple/resetPassword.php");
				exit(0);
			}else{
				if($clearance>0)
					$_SESSION["verified"] = "v3r1f13d-adm1n";
				else
					$_SESSION["verified"] = "v3r1f13d";
				header("Location: /inventory/apple/userPanel.php");
				exit(0);
			}
		}else{
			$incorrect = true;
		}
	}
?>

<html>
<head>
	<meta charset="utf-8">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<title>Inventory - Apple Sign In</title>
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
	<link rel="stylesheet" type="text/css" href="../style.css">
	<link rel="shortcut icon" href="../favicon.ico" />
     
</head>

<body>
	<div id="title" class="contain">
		<span class="textcenter" id="titleText">
			<h1><strong>Inventory</strong> - <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Apple_logo_grey.svg/391px-Apple_logo_grey.svg.png" style="width: 5%;"> Sign In</h1>
		</span>
	</div>
	<div class="contain">
		<img src="../images/UCSLogos.png">	
	</div>
	<?php
		if($incorrect){
			echo "<div class=\"contain\"><p style=\"color: red; font-size: 17px;\">The username or password entered is incorrect.</p></div>";
		}
	?>
	<div class="contain">
		<form action="index.php" method="post" enctype="multipart/form-data">
			<div class="center"><label for="firstname">Username: </label><input type="text" name="username" required><br></div>
			<div class="center"><label for="firstname">Password: </label><input type="password" name="password" required><br></div>
			<div class="subForm">
				<div class="g-recaptcha" data-sitekey="6Lcx0FcUAAAAALSrvNmvKxCTqqKfjMGsaIu7dqQm" style="margin-left: auto; margin-right: auto; display: block; width: 50%;"></div>
				<span>
                    <div class="centerButton"><button type="submit" name="submit" value="Submit">Submit</button></div>
				</span>
			</div>
		</form>
	</div>

<footer>
	<hr>
    	<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
</footer>
</body>
</html>*/
?>
