<?php
$head = ['title' => "Clear Box",
	'scripts' => "<script>
		 function showsubmit(){
			$('#submit').css('visibility', 'visible');
			window.alert('PLEASE BE SURE YOU WANT TO DELETE THE BOX DATA!');
		}
	</script>"];
	$header = "Clear Box";
	require "classes/header.php";

?>
	<div class="contain">
		<p>This will <strong>clear all data that has been scanned for the specified box.</strong><br>Please be sure you want to do this before submitting.</p>
	</div>
	<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="clearbox.php" method="post">
		<div class="subForm">
			<?php include "classes/departmentSelector.html"; include "classes/storeSelector.html"; ?>
			<label>
			<p class="center">Fixture Number:</p>
			<input class="center" type="text" name="fixNum" required></label><br>
			<label>
			<p class="center">Box Number:</p>
			<input class="center" type="text" name="boxNum" required></label><br>
			<span>
				<input class="button textcenter" style="width: 130px;"  onclick="showsubmit()" value="clear box" type="button" title="Clear box"/><br>
				<input class="button textcenter" style="width: 230px; background: red; visibility: hidden;" type="submit" id="submit" name="submit" value="okay, really clear box" title="Okay, really clear box"/>
			</span>
		</div>
	</form>
	</div>
	<?php include "classes/footer.php"; ?>
