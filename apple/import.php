<?php
$apple = true;
$head = ['title'=>'Import MBS Report'];
$header = "Import MBS Report";
include '../classes/header.php';
?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="importFile.php" method="post">
		<div class="center space-bottom" style="width: 55%;">
			<span><strong style="text-align: center">MBS Report:</strong>
				<input type="file" size="25" id="report" name="report" tabindex="2" required>
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
