<?php
$apple = true;
$head = ['title'=>'Delete by Day',
	'scripts'=>'<script type="text/javascript">
	var e = jQuery.noConflict();
e( function() {
	e( ".datepicker" ).datepicker({
	dateFormat: "yy-mm-dd"
			});
		} );
	</script>'
];
$header = "Delete by Day";
include '../classes/header.php';
?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="delete_day_action.php" method="post" autocomplete="off">
		<div style="text-align: center">
			<label for="drb">Start Date:</label>
			<input type="text" name="drb" class="datepicker" size="10">
		</div>
		<div style="text-align: center">
			<label for="drb">End Date:</label>
			<input type="text" name="dre" class="datepicker" size="10">
		</div>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13"/>
			</span>
		</div>
	</form>
</div>
<?php include '../classes/footer.php'; ?>
