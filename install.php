<?php
if(isset($_POST) && !empty($_POST)){
	$dbFile = fopen("classes/db.php","w");
	fwrite($dbFile,"<?php \n");
	fwrite($dbFile,'$host = "'.$_POST['host'].'";'."\n");
	fwrite($dbFile,'$user = "'.$_POST['user'].'";'."\n");
	fwrite($dbFile,'$pass = "'.$_POST['pass'].'";'."\n");
	fwrite($dbFile,'$db = "'.$_POST['db'].'";'."\n");

	fwrite($dbFile,'$conn = mysqli_connect($host,$user,$pass,$db);'."\n");
	fwrite($dbFile,'if(!$conn){'."\n");
	fwrite($dbFile,"\t echo('MYSQL ERROR: '.mysqli_connect_error());\n");
	fwrite($dbFile,"\t die();\n ");
	fwrite($dbFile,"}\n?>");

	$status = "DB file written.";

	$conn = mysqli_connect($_POST['host'],$_POST['user'],$_POST['pass']);
	if(!$conn)
		die("MYSQL ERROR: ".mysqli_connect_error());

	$query = "CREATE DATABASE `{$_POST['db']}`";
	if(mysqli_query($conn,$query))
		$status .= "<br>Database created successfully.";
	else
		$status .= "<br>Database creation failed!";

	$shellCommand = "mysql --user={$_POST['user']} --password='{$_POST['pass']}' -h {$_POST['host']} -D {$_POST['db']} < install.sql";
	exec($shellCommand,$output,$returnVal);

	if($returnVal==0)
		$status .= "<br>Tables initialized successsfully.";
	else
		$status .= "<br>MySQL table import failure: Shell returned code $returnVal.";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<title>Welcome to Inventory!</title>

		<link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="/inventory/style.css">
		<link rel="shortcut icon" href="/inventory/favicon.ico" />
	</head>

	<body>
		<div id="title" class="contain">
			<span class="textcenter" id="titleText">
				<h1>Set up Inventory</h1>
			</span>
		</div>
		<div class="contain">
			<img src="/inventory/images/UCSLogos.png" alt="University Campus Store">
		</div>

		<?php if(!isset($_POST) || empty($_POST)):?>
		<div class="contain">
			Welcome to the Inventory application! Submit your database information to get started.<br>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<label>
					Hostname:
					<input type="text" name="host" value="localhost">
				</label><br>
				<label>
					Username:
					<input type="text" name="user" >
				</label><br>
				<label>
					Password:
					<input type="password" name="pass" >
				</label><br>
				<label>
					Database name:
					<input type="text" name="db" >
				</label>
				<br>
				<input type="submit" name="sub" value="Submit">
			</form>
		</div>
		<?php else:?>
		<div class="contain">
			Script ran! Returned status: <br><?php echo $status; ?>
		</div>
		<?php endif; ?>


	</body>
</html>
