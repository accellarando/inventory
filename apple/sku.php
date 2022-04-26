<?php
	session_start();
	include '../session-timeout.php';
		if($_SESSION["verified"] != "v3r1f13d" && $_SESSION['verified'] != "v3r1f13d-adm1n"){
		header("Location: /inventory/apple/index.php");
	}
	
	$_SESSION["scan-type"] = "sku";
	
	//saves scanned barcodes to session
	if(isset($_POST["barcode"])){
		
		$infoarray = array();
		
		//grab all the other barcodes
		if(isset($_SESSION["barcode"])){
			$infoarray = $_SESSION["barcode"];
		}
		
		//gets subarray by key
		$currentkey = $_POST["barcode"];
		$currentarray = array();
		if(isset($infoarray[$currentkey])){
			$currentarray = $infoarray[$currentkey];
			$qty = (int)$currentarray[0];
			$qty++;
			$currentarray[0] = $qty;
		}else{
			//if this is the first time scanning this item, a value of 1 is pushed to its own array
			$currentarray[0] = 1;
		}
		
		$infoarray[$currentkey] = $currentarray;
		
		$_SESSION["barcode"] = $infoarray;
	}
?>
<html>
	<head>
		<title>Scanning Page</title>	
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
		function doneScanning(){
			window.location.href = "confirm-scanned.php";
		}
	</script>
	</head>
	
	<body>
		<div id="title" class="contain">
			<span class="textcenter" id="titleText">
				<h1><strong>SKU</strong> Scanning Page</h1>
			</span>
		</div>
     <!--<div class=\"logos\"><img src=\"images/auxheader.png\" alt=\"University of Utah Auxiliary Services logo\" /></div>-->
	<div class="contain">
		<form align="center" method="post" action="sku.php">
			<!--<p>Click inside the textbox, then scan the Barcode.</p>-->
			<input class="center-block" type="text" id="barcode" name="barcode" size="35" autofocus>
			<!-- add buttons for new fixture, new shelf, new box next box, and done scanning -->
			<button type="submit" name="submit" style="display: none;">Submit</button><br><br>
			<button type="button" name="done" onclick="doneScanning()">Done scanning</button>
			
		</form>
	</div><!-- end contain -->
	<div class="contain">
	<?php
		$descriptions = array();
		
		if(isset($_SESSION['descriptions'])){
			$descriptions = $_SESSION['descriptions'];
		}
	
		include_once("../classes/db.php");

		//print all the scanned data
		$data = $_SESSION["barcode"];
		$keys = array_keys($data);
		$descriptionKeys = array_keys($descriptions);
		
		//combine duplicate barcodes while printing data
		foreach($keys as $key){
			//get the array of fixture,box,shelf info with this barcode
			$currentarray = $data[$key];
			$desc = null;
			
			
			//we fetch the descriptions as we scan so we don't slow down the program
			if(in_array($key, $descriptionKeys)){
				$desc = $descriptions[$key];
			}else{
				$query = "SELECT (description) FROM apple WHERE sku = '".$key."';";
				$desc = $conn -> query($query)->fetch_assoc()['description'];
				$descriptions[$key] = $desc;
			}
			
			$qty = $currentarray[0];
		
			//update this for quantities of the same barcode
			echo('<p><strong>'.$qty.'</strong> - '.$key.' - '.$desc.'</p>');
			
			$_SESSION['descriptions'] = $descriptions;
		}
	?>
	</div>
        <footer>
			<hr>
			<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
		</footer>
	</body>
</html>
