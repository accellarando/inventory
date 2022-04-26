<?php
$head = ['title' => "Clear Shelf",
'scripts' => "<script>
function showsubmit(){
	$('#submit').css('visibility', 'visible');
	window.alert('PLEASE BE SURE YOU WANT TO DELETE THE SHELF DATA!');
		}
</script>"];
$header = "Clear Shelf";
require "classes/header.php";
?>

<div class="contain">
	<p>This will <strong>clear all data that has been scanned for the specified shelf.</strong><br>Please be sure you want to do this before submitting.</p>
</div>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="clearfix.php" method="post">
		<div class="subForm">
			<?php include "classes/departmentSelector.html"; include "classes/storeSelector.html"; ?>
			<p class="center">Shelf Number:</p>
			<input class="center" type="text" name="fixNum" required><br>
			<span>
				<input type="button" class="button textcenter" style="width: 130px;"  onclick="showsubmit()" value="clear shelf" title="clear shelf"/><br>
				<input class="button textcenter" style="width: 230px; background: red; visibility: hidden;" type="submit" id="submit" name="submit" title="Okay, really clear shelf" value="Okay, really clear shelf" />
			</span>
		</div>
	</form>
</div>
<?php include "classes/footer.php"; ?>
