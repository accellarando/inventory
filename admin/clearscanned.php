<?php
	session_start();
	
	include '../session-timeout.php';
	if($_SESSION["verified"] != "v3r1f13d-adm1n"){
		header("Location: http://www.info.campusstore.utah.edu/inventory/admin/index.php");
	}
	
	if($_POST["submit"] != "okay, really delete all data"){
		echo "<h1>403 -- Forbidden</h1>";
		die();
	}

	include_once("../classes/db.php");
	
	$query = "delete from scanned where 1;";
	
	if($conn->query($query) === TRUE){
		//echo out a prompt to the user if successful
		echo "<html><head>
		<meta charset=\"utf-8\">
		<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>
		
		<title>Database cleared</title>
		<!--<link rel=\"stylesheet\" type=\"text/css\" href=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.7/themes/smoothness/jquery-ui.css\">
		<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js\"></script>
		<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js\"></script>-->
		
		<link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css\">
		<link rel=\"stylesheet\" href=\"//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css\">
		<script src=\"https://code.jquery.com/jquery-1.12.4.js\"></script>
		<script src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.js\"></script>
		<script src=\"//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js\"></script>
		
		<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css\">
		<!--jquery.timepicker.min.css-->
		
		<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900\" rel=\"stylesheet\" type=\"text/css\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"../style.css\">
		 
	</head>

	<body>
		<div id=\"title\" class=\"contain\">
			<span class=\"textcenter\" id=\"titleText\">
				<h1>The database has been cleared!</h1>
			</span>
		</div>
		<div class=\"contain\">
			<img src=\"../images/UCSLogos.png\">
		</div>
		<div class=\"contain\">
			<p style=\"font-size: 18px; text-align:center;\">The database has been successfuly cleared. 
			<br><br>
			<a href=\"adminPanel.php\"><p style=\"text-align: center\">Back to Admin Panel</p></a>
		</div>

	<footer>
		<hr>
			<h4 class=\"textcenter\">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(\" - \"+new Date().getFullYear());</script></h4>
	</footer>
	</body>
	</html>";
	}else{
		echo "<h1>Error: Unable to clear database. Please contact the IT department.</h1>";
	}
	
	$conn->close();
?>
