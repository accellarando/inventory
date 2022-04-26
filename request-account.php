<?php
	session_start();
?>
<html lang="en">
<head>
	<meta charset="utf-8">
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<title>Request Account</title>
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
			<h1>Request Account</h1>
		</span>
	</div>
	<div class="contain">
		<img src="images/UCSLogos.png" alt="University Campus Store">
	</div>
	<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="send-account-request.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<label><strong>First Name:</strong>
				<input type="text" size="25" id="firstname" name="firstname" tabindex="2" required>
			</label>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<label><strong>Last Name:</strong>
				<input type="text" size="25" id="lastname" name="lastname" tabindex="2" required>
			</label>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 40%;">
			<label><strong>UnID:</strong>
				<input type="text" size="25" id="uid" name="unid" tabindex="2" required>
			</label>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<?php include 'classes/departmentSelector.html'; ?>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<span><strong>Account type:</strong>
				<select id="clearance" name="clearance" required>
					<option value="Please select" selected="true" disabled="disabled">Please select</option>
					<option value="0 - User">User</option>
					<option value="1 - Manager">Manager</option>
				</select>
			</span>
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
