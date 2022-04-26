<?php
$apple = true;
include '../classes/phpheader.php';

//import all the data
$query = "SELECT id FROM users WHERE username = '".$_SESSION['username']."';";
$userid = $conn->query($query)->fetch_assoc()['id'];

$data = $_SESSION["barcode"];
$keys = array_keys($data);

$sku = '';
$vendorStyleNum = '';

foreach($keys as $barcode){	
	//get the array of barcodes with this fixture, shelf and box number
	$currentarray = $data[$barcode];

	$qty = $currentarray[0];

	//insert data into the database
	//overwrite data if it already exists FROM TODAY
	$query = "SELECT (QTY) FROM apple_scanned WHERE ";

	if($_SESSION['scan-type'] == "sku"){
		$query .= "sku='".$barcode."' ";
		$sku = $barcode;
	}else{
		$query .= "vendorStyleNum='".$barcode."' ";
		$vendorStyleNum = $barcode;
	}

	//gets current date
	$month = date('m');
	$year = date('Y');
	$day = date('d');

	$query .= "AND DATE_FORMAT(date, '%Y-%m-%d') = '".$year."-".$month."-".$day."';";

	$result = $conn->query($query)->fetch_assoc();

	//delete old data if it exists
	if($result != null){
		//trim beginning bit off query
		$query = substr($query, strpos($query, ")")+1);
		$query = "DELETE".$query;
		$conn->query($query);

		$qty = $qty + $result['QTY'];
	}

	if($sku=='')
		$sku = getAppleDetails($vendorStyleNum, "vendorStyleNum")['column'];
	if($vendorStyleNum==''){
		$vendorStyleNum = getAppleDetails($sku, "sku")['column'];
	}

	$query = "INSERT into apple_scanned (sku, scanner_id, QTY, vendorStyleNum) VALUES ('".$sku."','".$userid."','".$qty."','".$vendorStyleNum."');";
	$conn->query($query);
}

$head = ['title'=>'Data has been imported!'];
$header = "<strong>Data</strong> has been imported!";
include '../classes/header.php';
?>
<div class="contain" style="text-align: center;">
	<p>The scanned data has been imported!</p><br><br>
</div>
<?php include '../classes/footer.php'; ?>
