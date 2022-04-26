<?php
$head = ['title' => "Clear Fixture",
'scripts' => "<script>
function showsubmit(){
	$('#submit').css('visibility', 'visible');
	window.alert('PLEASE BE SURE YOU WANT TO DELETE THE FIXTURE DATA!');
		}
</script>"];
$header = "Clear Fixture";
require "classes/header.php";
?>

<div class="contain">
	<p>This will <strong>clear all data that has been scanned for the specified fixture.</strong><br>Please be sure you want to do this before submitting.</p>
</div>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="clearfix.php" method="post">
		<div class="subForm">
			<?php include "classes/departmentSelector.html"; include "classes/storeSelector.html"; ?>
			<label>
			<p class="center">Fixture Number:</p>
			<input class="center" type="text" name="fixNum" required><br>
			</label>
			<span>
				<input type="button" class="button textcenter" style="width: 130px;"  onclick="showsubmit()" value="clear fixture" title="clear fixture"/><br>
				<input class="button textcenter" style="width: 230px; background: red; visibility: hidden;" type="submit" id="submit" name="submit" value="okay, really clear fixture" title="okay, really clear fixture"/>
			</span>
		</div>
	</form>
</div>
<?php include "classes/footer.php"; ?>
