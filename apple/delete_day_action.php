<?php
include '../classes/phpheader.php';

require '../vendor/autoload.php';

$query = "DELETE FROM apple_scanned WHERE DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '".$_POST['drb']."' AND '".$_POST['dre']."';";
$conn->query($query);

$apple = true;
$head = ['title'=>'Data has been deleted'];
$header = "<strong>Data</strong> has been deleted.";
include '../classes/header.php';
?>
<div class="contain" style="text-align: center;">
	<p>The scanned data for the requested day has been deleted.</p><br><br>
</div>
<?php include '../classes/footer.php'; ?>
