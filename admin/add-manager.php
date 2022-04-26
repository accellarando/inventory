<?php
$admin = true;
include '../classes/phpheader.php';
	
	$query = "SELECT (clearance) FROM users WHERE username ='".$_SESSION['username']."';";
	$clearance = $conn->query($query)->fetch_assoc()['clearance'];
	
	if((int)$clearance < 2){
		echo "<h1>ERROR 403 --- FORBIDDEN</h1>";
		die();
	}

$head = ['title'=>"Add a Manager"];
$header = "Add a Manager";
include '../classes/header.php';
?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="addmanager.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<span><strong>First Name:</strong>
				<input type="text" size="25" id="firstname" name="firstname" tabindex="2" required>
			</span>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<span><strong>Last Name:</strong>
				<input type="text" size="25" id="lastname" name="lastname" tabindex="2" required>
			</span>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 40%;">
			<span><strong>UnID:</strong>
				<input type="text" size="25" id="uid" name="unid" tabindex="2" required>
			</span>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<span><strong>Password:</strong>
				<input type="password" size="25" id="password" name="password" tabindex="2" required>
			</span>
		</div><br>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13"/>
			</span>
		</div>
	</form>
</div>
<?php include '../classes/footer.php'; ?>
