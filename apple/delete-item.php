<?php
include "../classes/phpheader.php";

$query = "SELECT id FROM apple_scanned WHERE sku = '".$_POST['item']."';";
$result = $conn->query($query)->fetch_assoc();

if(empty($result)){
	$query = "SELECT id FROM apple_scanned WHERE vendorStyleNum = '".$_POST['item']."';";
	$result = $conn->query($query)->fetch_assoc();
}

$month = date('m');
$year = date('Y');

$query = "DELETE FROM apple_scanned WHERE id='".$result['id']."' AND DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '".$year."-".$month."-01' AND '".$year."-".$month."-31';";

$conn->query($query);

$apple = true;
$head = ['title'=>'Data has been deleted!'];
$header = "<strong>Scanned</strong> Data has been deleted.";
include '../classes/header.php';
?>
<div class="contain" style="text-align: center;">
	<p>The scanned data for the sku/vendorStyleNumber has been deleted.</p><br><br>
</div>
<?php include '../classes/footer.php'; ?>
