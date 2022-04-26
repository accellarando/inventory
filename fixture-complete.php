<?php
$head = ['title' => 'Mark Fixture Complete',
'scripts' =>"<script>
		function reset(){
		   //reset session scanned data
		   //not how that works jimmy!
		   //reset other session variables
			
			//redirect
			window.location.href = 'index.php';
		}
		
		$(document).ready(function(){
			$('#reset').on('click',function(){
				//reset session scanned data
				//reset other session variables
				
				//redirect
				window.location.href = 'index.php';
			});
		});
</script>"];
$header = "Mark Fixture <strong>Complete</strong>";

include 'classes/header.php';
?>

<div class="contain" style="text-align: center;">
	<h2>Is <strong>fixture <?php echo $_SESSION['fixnum']; ?></strong> complete?</h2>
<?php
//print all fixture data already in db
$dept = $_SESSION['dept'];
$store = $_SESSION['storenum'];
$fixt = $_SESSION['fixnum'];

//Get items already in the db
$query = "SELECT * FROM scanned WHERE dept='$dept' AND store='$store' AND fixtureNum='$fixt'";
$items = mysqli_fetch_all(mysqli_query($conn,$query),MYSQLI_ASSOC);
foreach($items as $item){
	$qty = $item["QTY"];
	$key = $item["sku"];

	$desc = getItemDetails($key,$_SESSION['storenum'])['description'];

	echo("<p><strong> $qty </strong> - $key - $desc </p>");

}

//print all the scanned data
$data = $_SESSION["barcode"];
$keys = array_keys($data);
$descriptions = $_SESSION['descriptions'];

//combine duplicate barcodes while printing data
foreach($keys as $key){
	$wrongFixt=false;

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
		//Does this apply to this fixture? if not, don't display it.
		//we'll worry about the rest of the logic on the other side of the form submission.
		if((substr($datastring, 0, strpos($datastring, "-", 0)))!==$_SESSION['fixnum'])
			$wrongFixt=true;
		$additionalQty = (int)(substr($datastring, strrpos($datastring, "-")+1));
		$qty = $qty + $additionalQty;
	}

	//update this for quantities of the same barcode
	if(!$wrongFixt) 
		echo('<p><strong>'.$qty.'</strong> - '.$key.' - '.$desc.'</p>');
}
?>
<form align="center" method="post" action="import-scanned-data.php">
	<input type="hidden" name="fixtComplete" value="1">
	<label>Comments: <input type="text" name="comments"></label>
	<br>
	<input class="button textcenter" style="width: 180px; display: inline-block; line-height:30px;" name="reset" value="No, keep scanning" type="submit">
	<input class="button textcenter" style="width: 180px;" type="submit" id="submit" name="submit" value="This is correct, submit" tabindex="13" readonly />
</form>
<small>Note: this includes data from previous sessions, not just this scanning session.</small>
</div><!-- end contain -->

<?php include 'classes/footer.php'; ?>
