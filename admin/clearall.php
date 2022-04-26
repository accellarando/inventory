<?php
$admin = true;
$head = ['title' => 'Clear all data',
	'scripts' => " <script>
		 function showsubmit(){
			$('#submit').css('visibility', 'visible');
			window.alert('PLEASE BE SURE YOU WANT TO DELETE ALL DATA!');
		}
	 </script>"];
$header = "Clear all data";
include '../classes/header.php';
?>
<div class="contain">
	<p>This will <strong>clear all data that has been scanned by any users.</strong><br>This does not clear the validation files.<br>Please be sure you want to do this before submitting.</p>
</div>
<form name="postForm" id="postForm"  enctype="multipart/form-data" action="clearscanned.php" method="post">
	</div><br>
	<div class="subForm">
		<span>
			<input type="button" class="button textcenter" style="width: 130px;"  onclick="showsubmit()" value="clear all data" tabindex="13"/><br>
			<input type="button" class="button textcenter" style="width: 230px; background: red; visibility: hidden;" type="submit" id="submit" name="submit" value="okay, really delete all data" tabindex="13"/>
		</span>
	</div>
</form>

<?php include '../classes/footer.php'; ?>
