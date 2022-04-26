<?php
$apple = true;
$head = ['title'=>'Verify Scanned Items'];
$header = "<strong>Verify</strong> Scanned Items";
include '../classes/header.php';
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

	$qty = $currentarray[0];

	//update this for quantities of the same barcode
	echo('<p><strong>'.$qty.'</strong> - '.$key.' - '.$desc.'</p>');
}
?>
<form align="center" method="post" action="import-scanned-data.php">
	<input class="button textcenter" style="width: 220px;" id="reset" name="reset" value="There is an error, Start over" tabindex="13"/ readonly >
	<input class="button textcenter" style="width: 180px;" type="submit" id="submit" name="submit" value="This is correct, Submit" tabindex="13" readonly />
</form>
</div><!-- end contain -->
<script type="text/javascript">
function reset(){
	//reset session scanned data
	sessionStorage.removeItem('barcode');
	//reset other session variables
	sessionStorage.removeItem('fixnum');
	sessionStorage.removeItem('shelfnum');
	sessionStorage.removeItem('boxnum');

	//redirect
	window.location.href = "index.php";
}

$(document).ready(function(){
	$("#reset").on("click",function(){
		//reset session scanned data
		sessionStorage.removeItem('barcode');
		//reset other session variables
		sessionStorage.removeItem('fixnum');
		sessionStorage.removeItem('shelfnum');
		sessionStorage.removeItem('boxnum');

		//redirect
		window.location.href = "index.php";
	});
});
</script>
<?php include '../classes/footer.php'; ?>
