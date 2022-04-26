<?php
$head=['title'=>"Report Invalid Data"];
include __DIR__."/classes/header.php";

if(isset($_POST['identifier']))
	$sku = substr($_POST['identifier'],0,strpos($_POST['identifier'], '-'));
else if(isset($_POST['sku']))
	$sku = $_POST['sku'];

$details = getItemDetails($sku,$_SESSION['storenum']);

$query = "SELECT * FROM users WHERE clearance=1";
$managers = mysqli_fetch_all(mysqli_query($conn, $query), MYSQLI_ASSOC);

$query = "SELECT name, manager_id FROM users WHERE username='".$_SESSION['username']."'"; 
// echo "<pre>";var_dump($_SESSION); echo "</pre>";
$me = mysqli_fetch_assoc(mysqli_query($conn, $query));

if(isset($_POST['sendEmail'])){
	require("../Libraries/PHPMailer_v5.1/class.phpmailer.php");
	$mail = new PHPMailer(true);
	$datetime = new DateTime("now", new DateTimeZone("America/Denver"));

	$webmaster_email = "no-reply@bookstore.utah.edu"; //Reply to this email ID
	$mail->From = $webmaster_email;
	$mail->FromName = "Inventory Application";
	$mail->AddReplyTo($webmaster_email,"Webmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->IsHTML(true); // send as HTML
	$mail->Subject = "Inventory Bad Tag";

	$mail->Body = $me['name']." would like to submit a SKU with invalid data for your review.<br><br>";
	$mail->Body .= "<strong>SKU:</strong> ".$sku."<br>";
	$mail->Body .= "<strong>Description:</strong> ".$_POST['desc']."<br>";
	$mail->Body .= "<strong>Price:</strong> ".$_POST['price']."<br>";
	$dbdetails = getItemDetails($sku,$_SESSION['storenum']) ?? ['description' => 'None', 'price' => '0'];
	$mail->Body .= "<strong>Description in the database:</strong> ".$dbdetails['description']."<br>";
	$mail->Body .= "<strong>Price in the database:</strong>  $".$dbdetails['price']."<br>";
	$mail->Body .= "<strong>Comments:</strong> ".$_POST['comments']."<br>";
	$mail->Body .= "<strong>Date:</strong> ".$datetime->format("Y-m-d H:i")."<br>";

	$query = "SELECT * FROM users WHERE id=".$_POST['manager'];
	$manager = mysqli_fetch_assoc(mysqli_query($conn,$query));
	$email = $manager['username']."@umail.utah.edu"; 
	$name = $manager['name']; 

	$mail->AddAddress($email, $name);

	// $mail->AddAddress("emoss@campusstore.utah.edu", "Ella Moss");
	//Send the email, report an error if the send fails
	if(!$mail->Send()) {
		$errorText = "Mailer Error: " . $mail->ErrorInfo . "\n Contact IT.";
		handleError("Mailer Error");
	}
	else {
		echo "<div class='contain'>The report has been sent successfully.<br>
		<button onclick=\"window.location='scan.php'\">Continue Scanning</button>
		</div>";
		include __DIR__.'/classes/footer.php'; die();
	}

}

?>
<div class="contain">
	<form action="report-sku.php" method="POST">
		<label for="sku">Problematic SKU:</label>
		<input type='text' name="sku" id="sku" value="<?php echo $sku; ?>" required><br><br>

		<label for="desc">Item description:</label>
		<input type='text' name="desc" id="desc" value="<?php echo $details['description']; ?>" required><br><br>

		<label for="price">Item price:</label>
		<input type='text' name="price" id="price" value="<?php echo $details['price']; ?>" required><br><br>

		<label for="managers">Send to:</label>
		<select name="manager" id="managers">
			<?php foreach($managers as $manager):?>
			<option value='<?php echo $manager['id'];?>'
			<?php if($manager['id']===$me['manager_id']) echo "selected"; ?>>
			<?php echo $manager['name']; ?>
			</option>
			<?php endforeach; ?>
		</select><br><br>

		<label for="comments">Comments:</label><br>
		<textarea name="comments" id="comments" rows="4" cols="50"></textarea>
		<br><br>

		<button type="submit" name='sendEmail'>Submit</button>
	</form>
</div>
<?php include __DIR__.'/classes/footer.php'; ?>
