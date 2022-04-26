<?php
$admin = true;
$head = ['title'=>'Remove a User'];
$header = "Remove a User";
include '../classes/header.php';
?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="remove-user.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<span><strong>UnID:</strong>
				<input type="text" size="25" id="uid" name="unid" tabindex="2" required>
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
