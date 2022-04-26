<?php
//removes leftover barcode data, if any
$_SESSION["barcode"] = null;

$apple = true;
$head = ['title'=>'Remove Scanned Data'];
$header = "<strong>Remove</strong> Scanned Data";
include '../classes/header.php';
?>
<div class="contain" style="height: 150px;">
	<form enctype="multipart/form-data" method="post" action="delete-item.php">
		<div class="center space-bottom" style="width: 100%;text-align: center;">
			<label for="item">SKU or VendorStyleNum:</label>
		</div>
		<div class="center space-bottom" style="width: 100%;text-align: center;">
			<input type="text" size="15" name="item">
		</div>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13"/>
			</span>
		</div>
	</form>
</div>
<?php include '../classes/footer.php'; ?>
