<?php
$head = ['title' => 'Verify Items to be Removed'];
$header = "<strong>Verify</strong> Items to be Removed";
include 'classes/header.php';
?>

<div class="contain" style="text-align: center;">
	<h2>Are these changes correct?</h2>
Store <?php echo $_SESSION['storenum']; ?>, department <?php echo $_SESSION['dept']; ?>, fixture <?php echo $_SESSION['fixnum'];
	if($_SESSION['shelfnum'])
		echo ", shelf ".$_SESSION['shelfnum'];
	if($_SESSION['boxnum'])
		echo ", box ".$_SESSION['boxnum'];
	?>
	<br>
<?php

foreach($_SESSION['changes'] as $sku => $change){
	if(in_array($sku, array_keys($_SESSION['descriptions']))){
			$desc = $_SESSION['descriptions'][$sku];
		}
		else{
			$desc = getItemDetails($sku,$_SESSION['storenum'])['description'];
		}
	if(isset($_SESSION['items'][$sku]))
		$total = $_SESSION['items'][$sku]['QTY'];
	else
		$total=0;
	echo "<br>";
	echo "<strong>$sku</strong>"." - ".$desc." - ";
	echo ($change<0) ? "Removed " : "Added ";
	echo abs($change)." - ";
	echo "$total total in unit."; 
}
?> 
<form align="center" method="post" action="remove-scanned-data.php">
	<input class="button textcenter" style="width: 220px;" id="reset" name="reset" value="There is an error, Start over" tabindex="13" type="submit" >
	<input class="button textcenter" style="width: 180px;" type="submit" id="submit" name="submit" value="This is correct, Submit" tabindex="13" type="button" >
</form>
</div><!-- end contain -->
<?php include 'classes/footer.php'; ?>
