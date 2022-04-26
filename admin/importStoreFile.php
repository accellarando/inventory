<?php
$admin = true;
$head = ['title'=>'Import Store File'];
$header = "Import Validation File";
include '../classes/header.php';
?>
<div class="contain">
	<h3>Information:</h3>
	<p>Select Validation Files to upload.</p>
	<p>Please include the store numbers in your filenames.</p>
	<p>After clicking submit, you may close this window; the upload will run in the background.</p>
</div>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="uploadFile.php" method="post">
		<div class="centered space-bottom">
			<span><strong>Validation CSV Files:</strong>
				<input type="file" size="25" id="csv" name="csvs[]" tabindex="2" multiple required>
			</span>
		</div><br>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13"/>
			</span>
		</div>
	</form>
</div>
<?php include '../classes/footer.php'; ?>
