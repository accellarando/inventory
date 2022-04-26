<?php
session_start();

if($_POST["dept"] == ""){
    die("<h2>Please select a valid department.</h2>");
}

include_once("classes/db.php");

$unid = $_POST['unid'];
$query = "SELECT 1 FROM users WHERE username='$unid'";
if(mysqli_num_rows(mysqli_query($conn,$query))>0){
    echo "<h2>You already have an account with that UNID. Contact IT if you need your password reset.</h2>";
    exit();
}

require("../Libraries/PHPMailer_v5.1/class.phpmailer.php");
//SMTP config needs to be set up in php.ini
$mail = new PHPMailer();

$webmaster_email = "no-reply@bookstore.utah.edu"; //Reply to this email ID
$mail->From = $webmaster_email;
$mail->FromName = "Inventory Application";
$mail->AddReplyTo($webmaster_email,"Webmaster");
$mail->WordWrap = 50; // set word wrap
$mail->IsHTML(true); // send as HTML
$mail->Subject = "Account Request by ". $_POST['firstname']." ".$_POST["lastname"];

$mail->Body = "Account Request by <strong>". $_POST['firstname']." ".$_POST["lastname"]."</strong><br>UnID: <strong>".$_POST["unid"]."</strong><br>Department: <strong>".$_POST["dept"]."</strong><br>Clearance requested: ".$_POST['clearance']."<br>When their account is setup, email their temporary password to: ".$_POST["unid"]."@umail.utah.edu";

//**************************************************


$mail->AddAddress("sysadmin@example.com", "Admin's Email");

//***************************************************************************************************************************************

//Send the email, report an error if the send fails that directs the user to contact the store.
if(!$mail->Send()) {
    echo "<p>Mailer Error: " . $mail->ErrorInfo;
    echo "The form failed to submit properly, please try again later or contact".
	" a representative of the University Campus Store Order Fulfillment department for assistance.</p>".
	"<p>The Campus Store Order fulfillment office is typically open Mon - Friday from 7:30AM to 5:00 PM MST".
	"and is closed on holidays.</p>".
	"<h4>You can contact the them by calling - 801-585-3234</h4>";
    die();
}
?>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<title>Inventory - Sign In</title>

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
		<h1><strong>Request Submitted</h1>
	    </span>
	</div>
	<div class="contain">
	    <img src="images/UCSLogos.png" alt="University Campus Store">	
	</div>
	<div class="contain">
	    <p style="text-align: center;"><strong>Your account request has been submitted.</strong></p><br><meta http-equiv="refresh" content="5;url=index.php" />
	</div>
    </body>
</html>
