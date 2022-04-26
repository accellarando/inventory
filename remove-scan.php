<?php

$head = ['title'=>"Edit Scanned Data"];
$header = "<strong>Edit</strong> Scanned Data";
include 'classes/header.php';

//removes left over data if any, so it doesn't get confused
removeScanningData();
unset($_SESSION['items']);
unset($_SESSION['changes']);

?>
<div class="contain">
	<form name="postForm" id="postForm"  enctype="multipart/form-data" action="remove.php" method="post">
		<div style="margin: 0 auto; padding-top: 10px; width: 70%;">
				<?php include 'classes/departmentSelector.html'; ?>
				<?php include 'classes/storeSelector.html'; ?>
		</div><br>
		<div style="margin: 0 auto; padding-top: 10px; width: 85%;">
			<span><strong>Fixture #:</strong>
				<input type="text" size="10" id="fixnum" name="fixnum" tabindex="2" required
					value="<?php if(isset($_POST['fixture'])) echo htmlentities($_POST['fixture']); ?>">
			</span>
			<span><strong style="margin-left: 20px;"> Shelf #:</strong>
				<input type="text" size="10" id="shelfnum" name="shelfnum" tabindex="2"
					value="<?php if(isset($_POST['shelf'])) echo htmlentities($_POST['shelf']); ?>">
			</span>
			<span><strong style="margin-left: 20px;"> Box #:</strong>
				<input type="text" size="4" id="boxnum" name="boxnum" tabindex="2"
					value="<?php if(isset($_POST['box'])) echo htmlentities($_POST['box']); ?>">
			</span>
		</div><br>
		<div class="subForm">
			<span>
				<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13" readonly />
			</span>
		</div>
	</form>
</div>
<?php if(isset($_POST)): ?>
<script>
$(window).on("load", function(){
	selectedDept ='<?php echo htmlentities($_POST['dept']);?>';
	selectedStore = '<?php echo htmlentities($_POST['store']);?>';

	//get the "dept" select
	deptOptions = $("#dept").children("option");
	//iterate through options
	for(var i = 0; i<deptOptions.length; i++){
		if($(deptOptions[i]).val()==selectedDept)
			//if option value = selectedDept, add "selected
			$(deptOptions[i]).prop("selected","selected");
	}

	//repeat for store
	storeOptions = $("#storenum").children("option");
	for(var i = 0; i<storeOptions.length; i++){
		if($(storeOptions[i]).val()==selectedStore)
			$(storeOptions[i]).prop("selected","selected");
	}
});
</script>
<?php endif;?>

<?php include 'classes/footer.php';?>
