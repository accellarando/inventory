<?php
$admin = true;
$head = ['title'=>'Expiring User Report',
	'scripts'=>'    <script>
		var e = jQuery.noConflict();
		e( function() {
			e( ".datepicker" ).datepicker({
				dateFormat: "yy-mm-dd"
			});
		} );
	</script>'];

$header = "Expiring User <strong>Report</strong>";
include '../classes/header.php';
?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="expired.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 50%;">
			<span><strong>Expiration Date:</strong>
				<input class="date datepicker" type="text" size="10" id="date" name="date" tabindex="2" required>
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
