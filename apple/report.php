<?php
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
	
	<title>Generate Report</title>
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
    <script type="text/javascript">
		var e = jQuery.noConflict();
		e( function() {
			e( ".datepicker" ).datepicker({
				dateFormat: 'yy-mm-dd'
			});
		} );
	</script>
</head>

<body>
	<div id="title" class="contain">
		<span class="textcenter" id="titleText">
			<h1>Generate Inventory Report</h1>
		</span>
	</div>
	<div class="contain">
		<img src="../images/UCSLogos.png">
	</div>
	<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="generate-report.php" method="post" autocomplete="off">
		<div style="text-align: center">
			<label for="drb">Date Range Begin:</label>
			<input type="text" name="drb" class="datepicker" size="10">
		</div>
		<div style="text-align: center">
			<label for="dre">Date Range End:</label>
			<input type="text" name="dre" class="datepicker" size="10">
		</div>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13"/>
			</span>
		</div>
	</form>
</div>
<footer>
	<hr>
    	<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
</footer>
</body>
</html>
