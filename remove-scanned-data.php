<?php
require 'classes/phpheader.php';

if(isset($_POST['reset'])){
	removeScanningData();
	unset($_SESSION['changes']);
	unset($_SESSION['items']);
	header("Location: remove-scan.php");
}

if(isset($_POST['submit'])){
	foreach($_SESSION['changes'] as $sku => $delta){
		$id = $_SESSION['items'][$sku]['id'];
		$newQty = mysqli_fetch_row(mysqli_query($conn,"SELECT QTY FROM scanned WHERE id=$id"))[0]+$delta;
		$query = ($newQty<=0) 
			? "DELETE FROM scanned WHERE id=$id" 
			: "UPDATE scanned SET QTY = $newQty WHERE id=$id";
		mysqli_query($conn,$query);
	}
}

$head = ['title'=>'Data has been updated!'];
$header = '<strong>Data</strong> has been updated!';
include 'classes/header.php';
?>
<div class="contain" style="text-align: center;">
<?php if( mysqli_error($conn))
echo mysqli_error($conn);
else{?>
	<p>The scanned data has been updated!</p><br><br>
	<a href="userPanel.php">Back to User Panel</a>
</div>
<?php } include 'classes/footer.php'; ?>
