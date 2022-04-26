<?php
$head = ['title' => 'Verify Scanned Items'];
$header = "<strong>Verify</strong> Scanned Items";

include 'classes/header.php';
?>

<div class="contain" style="text-align: center;">
	<h2>Does this information look correct?</h2>
<?php

//print all the scanned data
$data = $_SESSION["barcode"];
$keys = array_keys($data);
$descriptions = $_SESSION['descriptions'];

//combine duplicate barcodes while printing data
foreach($keys as $key){
	//get the array of fixture,box,shelf info with this barcode
	$currentarray = $data[$key];

	$desc = $descriptions[$key];

	//removes consecutive leading zeros (remove this if runtimes begin to slow down)
	$currentChar = 0;
	while($currentChar < strlen($key) && substr($key, $currentChar, 1) == "0"){
		$currentChar++;
	}
	$key = substr($key, $currentChar);

	$qty = 0;
	foreach($currentarray as $datastring){
		$additionalQty = (int)(substr($datastring, strrpos($datastring, "-")+1));
		$qty = $qty + $additionalQty;
	}

	//update this for quantities of the same barcode
	echo('<p><strong>'.$qty.'</strong> - '.$key.' - '.$desc.'</p>');
}
?>
<form align="center" method="post" action="import-scanned-data.php">
	<input type="submit" class="button textcenter" style="width: 220px;" id="reset" name="reset" value="There is an error, Start over" tabindex="13"/ readonly >
	<input class="button textcenter" style="width: 180px;" type="submit" id="submit" name="submit" value="This is correct, Submit" tabindex="13" readonly />
	<input class="button textcenter" style="width: 180px;" type="submit" id="submit" name="cancel" value="Exit" tabindex="13" readonly />
</form>
</div><!-- end contain -->
<?php include 'classes/footer.php'; ?>
