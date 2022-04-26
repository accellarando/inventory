<?php
$head = ['title' => 'Scanning Page'];
$header = "<strong>Scanning</strong> Page";
include 'classes/header.php';

removeScanningData();

?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="scan.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 70%;">
			<?php include 'classes/departmentSelector.html'; include 'classes/storeSelector.html'; ?>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 85%;">
			<label><strong>Fixture #:</strong>
				<input type="text" inputmode="numeric" size="4" id="fixnum" name="fixnum" tabindex="2" required>
			</label>
			<label><strong style="margin-left: 20px;"> Shelf #:</strong>
				<input type="text" inputmode="numeric" size="4" id="shelfnum" name="shelfnum" tabindex="2">
			</label>
			<label><strong style="margin-left: 20px;"> Box #:</strong>
				<input type="text" inputmode="numeric" size="4" id="boxnum" name="boxnum" tabindex="2">
			</label>
			<label><strong style="margin-left: 20px;"> Precount #:</strong>
				<input type="text" inputmode="numeric" size="4" name="precount">
				(optional)
			</label>
		</div><br>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13" readonly />
			</span>
		</div>
	</form>
</div>
<?php include 'classes/footer.php'; ?>
