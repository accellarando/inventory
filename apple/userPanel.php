<?php
	require_once "../recaptchalib.php";

	session_start();
	include '../session-timeout.php';

	if($_SESSION["verified"] != "v3r1f13d" && $_SESSION['verified'] != "v3r1f13d-adm1n"){
		header("Location: /inventory/apple/index.php");
	}
?>
<html>
<head>
	<meta charset="utf-8">
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<title>User Panel</title>
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
			<h1><strong>User</strong> Panel</h1>
		</span>
	</div>
	<div class="contain">
		<img src="../images/UCSLogos.png">
	</div>
	<div class="contain" style="height: 350px;">
		<br><br>
		<a href="scan.php"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" value="Scan" tabindex="13" readonly /></a><br><br>
		<a href="remove.php"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" value="Remove Data" tabindex="13" readonly /></a><br><br>
		<a href="import.php"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" value="Import File" tabindex="13" readonly /></a><br><br>
		<a href="report.php"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" value="Generate Report" tabindex="13" readonly /></a><br><br>
		<a href="lookup.php"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" value="Lookup" tabindex="13" readonly /></a><br><br><br><br>
    </div>
	<div class="contain" style="width: 5%; text-align: center;">
		<a href="logout.php">Log Out</a>
	</div>
<footer>
	<hr>
    	<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
</footer>
</body>
</html>
