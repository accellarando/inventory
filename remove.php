<?php
require('classes/phpheader.php');

//saves information from previous form to session
foreach($_POST as $key => $value){
	$_SESSION[$key]=$value;
}

if(!($_SESSION['dept'])||!($_SESSION['storenum'])||!($_SESSION['fixnum']))
	handleError("Must select store, department, and fixture!");

if(!isset($_SESSION['items'])){
	//Gets existing scanned items for the fixture, to display below
	$query = "SELECT * FROM scanned WHERE dept='".$_SESSION['dept']."' 
		AND store = '".$_SESSION['storenum']."'
		AND fixtureNum = '".$_SESSION['fixnum']."'
		AND shelfNum = '".$_SESSION['shelfnum']."'
		AND boxNum = '".$_SESSION['boxnum']."'";

	//If there are any results:
	$result = $conn->query($query);  
	// echo "<pre>";var_dump($query); echo "</pre>";
	if($result->num_rows > 0){
		$_SESSION['items']=array();
		$resultSet = mysqli_fetch_all($result,MYSQLI_ASSOC); 
		// echo "<pre>";var_dump($resultSet ); echo "</pre>";
		foreach($resultSet as $item){
			//$id = $item['id'];
			$sku = $item['sku'];
			$_SESSION['items'][$sku]=$item; 
		}
	}
	else{
		handleError("That one is already empty!");
	}

}

//Handle scanned barcode
if($_POST['barcode']){
	if(!in_array($_POST['barcode'],array_keys($_SESSION['items'])))
		handleError("Item not in this unit!","<a href='/inventory/remove.php'><button class='button'>Back</button></a>");
	$originalItem = $_SESSION['items'][$_POST['barcode']];
	$qty = $originalItem['QTY'];
	$qty--;
	$_SESSION['items'][$_POST['barcode']]['QTY']=$qty;
	if(isset($_SESSION['changes'][$_POST['barcode']]))
		$_SESSION['changes'][$_POST['barcode']] = $_SESSION['changes'][$_POST['barcode']]--;
	else
		$_SESSION['changes'][$_POST['barcode']] = -1;
}

$head = ['title' => 'Remove Scanned Data',
	'scripts' => '	<script type="text/javascript">
	function doneScanning(){
		window.location.href = "confirm-remove.php";
		}
	function showQtyEdit(sku){
			id = $("#qtyEdit"+String(sku));
			id.toggle();
			id.children("input").attr("autofocus",true);
			//event handler to "click" the right submit button
			id.children("input").on("keydown",function(e){
				if(e.key === "Enter"){
					e.preventDefault();
					id.children("button").click();
				}
			});
		}
	</script>'];
	$header = "<strong>Edit</strong> Scanned Data";
	include 'classes/header.php';
?>
<div class="contain">
	Currently <strong>removing</strong> from store <?php echo $_SESSION['storenum']; ?>, department <?php echo $_SESSION['dept']; ?>, fixture <?php echo $_SESSION['fixnum'];
	if($_SESSION['shelfnum'])
		echo ", shelf ".$_SESSION['shelfnum'];
	if($_SESSION['boxnum'])
		echo ", box ".$_SESSION['boxnum'];
	?>
</div>

<div class="contain">
	<form align="center" method="post" action="remove.php">
		<p>Scan to remove individual barcodes, or manually adjust counts below.</p>
		<input class="center-block" type="text" id="barcode" name="barcode" size="35" autofocus title="Scan or type barcode">
		<!-- add buttons for new fixture, new shelf, new box next box, and done scanning -->
		<button type="submit" name="submit" style="display: none;">Submit</button><br><br>

	</form>
</div><!-- end contain -->
<div class="contain">
	<strong>Items in unit:</strong>
<div style="display:grid; grid-template-columns: repeat(6,auto) 170px; align-items:top; row-gap:20px;">
<?php
	$descriptions = array();
	$descriptionKeys=array();
	if(isset($_SESSION['descriptions'])){
		$descriptions = $_SESSION['descriptions'];
		$descriptionKeys=array_keys($descriptions);
	}

	//print items in fixture
	foreach($_SESSION['items'] as $item){
		if($item['QTY']==0)
			continue;
		$sku = $item['sku'];

		if(in_array($sku, $descriptionKeys)){
			$desc = $descriptions[$sku];
		}
		else{
			$desc = getItemDetails($sku,$_SESSION['storenum'])['description']; //defined in helpers.php 
			$descriptions[$sku] = $desc;
		}
?>

<form action="remove-buttons.php" method="post" style="display:contents;">
	<span><strong ><?php echo $item['QTY']; ?></strong></span>
	<span> - </span>
	<span> <?php echo $sku; ?></span>
	<span> - </span>																	   
	<span><?php echo $desc;?></span>
	<span><?php echo substr($item['date'],0,10);?></span>
	<div style="display:grid; grid-template-columns: 60px repeat(3,auto); margin-top:0px; grid-template-rows: repeat(3,auto); column-gap:10px;">
		<input type="hidden" name="sku" value="<?php echo $item['sku'];?>">
		<input type="submit" name="remove" value="remove" style="grid-column: 1 / 2; width:70px; padding:0px;">
		<input type="button" onclick="showQtyEdit('<?php echo $sku;?>');" value="update quantity" style="grid-column: 2 / 5; width:100px; padding:0px;">
		<span id="qtyEdit<?php echo $sku;?>" class="hide-onload" style="display:contents;">
			<br>
			<label><span style="grid-row: 2 / 3; grid-column: 1 / 5">Enter new quantity:</span>
			<input type="number" inputmode="numeric" name="newQuantity" id="newQuantity<?php echo $sku;?>" style="grid-column: 1 / 4; width:80px;"></label>
			<button type="submit" name="update" value="" style="grid-column: 4 / 5">update</button>
		</span>
		<br>
	</div>
</form>


<?php $_SESSION['descriptions'] = $descriptions;
	}
?>

<br>

</div>
<button class="textcenter center" type="button" name="done" onclick="doneScanning()">Submit changes</button>
</div>
		<script>
		$(document).ready(function(){
			$(".hide-onload").hide();
		});
		</script>
<?php include 'classes/footer.php'; ?>
