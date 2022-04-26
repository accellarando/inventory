<?php
include 'classes/helpers.php';

if(isset($_POST['reset'])){
	echo "Resetting data.";
	unset($_SESSION['barcode']);
	unset($_SESSION['badCount']);
	unset($_SESSION['descriptions']);
	header("Location: /inventory/scan.php");
	die();
}
if(isset($_POST['cancel'])){
	echo "Resetting data.";
	removeScanningData();
	header("Location: /inventory/userPanel.php");
	die();
}

if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
	header("Location: /inventory/index.php");
	exit();
}

//import all the data

$query = "SELECT id FROM users WHERE username = '".$_SESSION['username']."';";
$userid = $conn->query($query)->fetch_assoc()['id'];

if(isset($_POST['fixtComplete'])){
	$store = $_SESSION['storenum'];
	$dept = $_SESSION['dept'];
	$fixt = $_SESSION['fixnum'];
	$comments = $_POST['comments'];

	$query = "INSERT INTO completed_fixtures (store, department, fixture, scanner_id, comments) 
		VALUES ('$store', '$dept', '$fixt', '$userid', '$comments');";
	//echo "<pre>";var_dump($query ); echo "</pre>";
	$conn->query($query);
}

$data = $_SESSION["barcode"];
$keys = array_keys($data);

foreach($keys as $barcode){	
	//get the array of barcodes with this fixture, shelf and box number
	$currentarray = $data[$barcode];

	foreach($currentarray as $datastring){
		//parse datastring for fixture, box, shelf and quantity
		$firstdash = strpos($datastring, '-');
		$lastdash = strrpos($datastring, '-');
		//word of the day: penultimate: adjective - last but one in a series of things; second last.
		$penultimatedash = strrpos(substr($datastring, 0, $lastdash), '-');

		$fixture = substr($datastring, 0, $firstdash);
		if(isset($_POST['fixtComplete'])){
			//we came from the mark fixture complete form
			if($fixture!=$_SESSION['fixnum'])
				continue;
		}

		$shelf = substr($datastring, $firstdash+1, ($penultimatedash - $firstdash-1));
		$box = substr($datastring, $penultimatedash+1, ($lastdash - $penultimatedash-1));
		$qty = substr($datastring, $lastdash+1);

		//insert data into the database
		//overwrite data if it already exists
		$query = "SELECT (QTY) FROM scanned WHERE dept='".$_SESSION["dept"]."' AND store='".$_SESSION["storenum"]."' AND fixtureNum='".$fixture."' AND shelfNum='".$shelf."' AND boxNum='".$box."' AND sku='".$barcode."';";
		$result = $conn->query($query)->fetch_assoc();

		//delete old data if it exists
		if($result != null){
			$query = "DELETE FROM scanned WHERE dept='".$_SESSION["dept"]."' AND store='".$_SESSION["storenum"]."' AND fixtureNum='".$fixture."' AND shelfNum='".$shelf."' AND boxNum='".$box."' AND sku='".$barcode."';";
			$conn->query($query);

			$qty = $qty + $result['QTY'];
		}

		$query = "INSERT into scanned (dept, store, fixtureNum, shelfNum, boxNum, QTY, sku, scanner_id) VALUES ('".$_SESSION["dept"]."','".$_SESSION["storenum"]."','".$fixture."','".$shelf."','".$box."','".$qty."','".$barcode."','".$userid."');";
		if($conn->query($query)){ //insertion was successful
			unset($_SESSION["barcode"][$barcode]);
		}
	}
}

$head = ['title'=>"Data has been imported!"];
$header = "<strong>Data</strong> has been imported!";

include 'classes/header.php';
?>
<div class="contain" style="text-align: center;">
	<p>The scanned data has been imported!</p><br><br>
	<form method="post" action="printing.php">
		<input type="hidden" name="dept" value="<?php echo $_SESSION['dept']; ?>" >
		<input type="hidden" name="store" value="<?php echo $_SESSION['storenum']; ?>" >
		<input type="hidden" name="fixture" value="<?php echo $_SESSION['fixnum']; ?>" >
		<input type="hidden" name="shelf" value="<?php echo $_SESSION['shelfnum']; ?>" >
		<input type="hidden" name="box" value="<?php echo $_SESSION['boxnum']; ?>" >

		<input class="button textcenter center" style="width:150px; margin: 0 auto" type="submit" name="submit" value="Print or Email Results">
	</form>

	<?php if(isset($_POST['fixtComplete'])){ ?>	
	<input class="button textcenter center" type="button textcenter center" name="submit" style="width:130px; margin: 0 auto;" readonly value="Continue Scanning" onclick="$('#hiddenForm').show()">
	<div id="hiddenForm" style="display:none;">
		<form action="scan.php" method="post">
			<p>Please enter new fixture number:</p>
			<input type="text" name="fixnum" style="width:130px; margin: 0 auto;">
			<br>
			<input class="button textcenter center" type="submit" name="submitNewFixt" style="width:130px; margin: 0 auto;" value="Confirm">
			<br>
	</div>

	<?php } else{ ?>
	<a href="scanning.php"><input class="button textcenter center" style="width:130px; margin: 0 auto;" value="Continue scanning"></a> 
	<?php } ?>

</div>
<?php include 'classes/footer.php'; ?>
