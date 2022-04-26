<?php
$head = ['title'=>'New Box'];
$header='New <strong>Box</strong>';
include 'classes/header.php';
?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="scan.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 150px; text-align: center;">
			<label><strong>New Box #:</strong><br><br>
				<input type="text" size="10" id="boxnum" name="boxnum" tabindex="2" required>
			</label>
		</div><br>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" title="Submit" id="submit" value="Submit" tabindex="13"/>
			</span>
		</div>
	</form>
</div>
<?php include 'classes/footer.php'; ?>
