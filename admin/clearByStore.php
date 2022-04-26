<?php
$admin = true;
$head = ['title' => 'Clear all data',
	'scripts' => " <script>
	function showsubmit(){
		$('#submit').css('visibility', 'visible');
		window.alert('PLEASE BE SURE YOU WANT TO DELETE ALL DATA FOR SELECTED STORE/DEPARTMENT!');
	}
	</script>"];
	$header = "Clear all data";
	include '../classes/header.php';
	?>
	<div class="contain">
		<p>This will <strong>clear all data that has been scanned for the selected store and/or department.</strong><br>This does not clear the validation files.<br>Please be sure you want to do this before submitting.</p>

		<form name="postForm" id="postForm"  enctype="multipart/form-data" action="clearScannedSpecific.php" method="post">
			<?php include '../classes/storeSelector.html'; include '../classes/departmentSelector.html'; ?>


			<div class="subForm">
				<span>
					<input type="button" class="button textcenter" style="width: 130px;"  onclick="showsubmit()" value="clear all data" tabindex="13"/><br>
					<input class="button textcenter" style="width: 230px; background: red; visibility: hidden;" type="submit" id="submit" name="submit" value="okay, really delete all data" tabindex="13"/>
				</span>
			</div>
		</form>
	</div>
	</div><br>
	<?php include '../classes/footer.php'; ?>
