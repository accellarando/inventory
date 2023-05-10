<?php 
$found = false;
$sku = $_POST['barcode'];
if(isset($sku)){
	include_once("classes/db.php");

	$query = "SELECT * FROM scanned WHERE sku LIKE '%$sku'";
	$stock = mysqli_fetch_all(mysqli_query($conn, $query),MYSQLI_ASSOC);

	if($stock)
		$found = true;
}

$head = ['title' => 'Item Lookup Page'];
$header = "Item <strong>Lookup</strong>";
include 'classes/header.php';
?>
<div class="contain">
	<form align="center" method="post" action="lookup.php">
		<!--<p>Click inside the textbox, then scan the Barcode.</p>-->
		<input class="center-block" type="text" id="barcode" name="barcode" size="35" autofocus title="Scan or type a barcode."><br><br>
	</form>
</div><!-- end contain -->

<div class="contain">
	<!-- Display item SKU and description -->
<?php 
if(isset($sku)){
	//Some SKUs in the scanned table still have leading zeros.

	$details = getItemDetails($sku); //helpers: returns (description, price)
	$description = ($details==NULL) ? "?" : $details["description"];
	$price = ($details == NULL) ? "$?" : $details["price"];
	echo "<h1>".$sku." - ".$description." - ".$price."</h1>";
}
?>
</div>

<div class="contain">
	<?php if(!isset($sku)){?>
	<h1>Enter or scan an item SKU to continue.</h1>
	<?php } else if(!$found && isset($sku)){ ?>
	<h1>SKU not found in database.</h1>
	<?php } else{ ?>
	<table class="table">
		<th>Quantity</th>
		<th>Store</th>
		<th>Department</th>
		<th>Fixture</th>
		<th>Shelf</th>
		<th>Box</th>
		<th>Last updated</th>
		<th>Scanned by</th>

		<?php foreach($stock as $item): ?>
		<tr>
			<td><?php echo $item['QTY'];?></td>		
			<td><?php echo $item['store'];?></td>		
			<td><?php echo $item['dept'];?></td>		
			<td><?php echo $item['fixtureNum'];?></td>		
			<td><?php echo $item['shelfNum'];?></td>		
			<td><?php echo $item['boxNum'];?></td>		
			<td><?php echo $item['date'];?></td>		
			<td><?php echo getUsername($item['scanner_id']);?></td>
		</tr>
		<?php endforeach; ?>
	</table> <?php } ?>
</div>

<?php include 'classes/footer.php'; ?>
