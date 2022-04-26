<?php
$head = ['title'=>'New Shelf'];
$header='New <strong>Shelf</strong>';
include 'classes/header.php';
?>
<div class="contain">
<form name="postForm" id="postForm"  enctype="multipart/form-data" action="scan.php" method="post">
			<div style="margin: 0 auto; padding-top: 10px; width: 150px; text-align: center;">
				<label><strong>New Shelf #:</strong><br><br>
					<input type="text" size="10" id="shelfnum" name="shelfnum" tabindex="2" required>
				</label>
				<!--<span><strong style="margin-left: 20px;"> box #:</strong>
					<input type="text" size="3" id="boxnum" name="boxnum" tabindex="2">
				</span>-->
			</div><br>
			<div class="subForm">
				<span>
					<input class="button textcenter" type="submit" id="submit" title="Submit" value="Submit" tabindex="13"/>
				</span>
			</div>
		</form>
</div>
<?php include 'classes/footer.php'; ?>
