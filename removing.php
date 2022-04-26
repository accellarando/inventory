<?php
$head = ['title'=>"Remove Scanned Data"];
$header = "<strong>Remove</strong> Scanned Data";
include 'classes/header.php'; 
?>
<div class="contain" style="height: 300px;">
	<br><br>
	<a href="remove-scan.php" aria-label="Scan or Manually Edit"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" title="Scan or Manually Edit" value="Scan or Manually Edit" tabindex="13" readonly /></a><br><br>
	<a href="clear-fixture.php" aria-label="Clear Fixture"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" title="Clear Fixture" value="Clear Fixture" tabindex="13" readonly /></a><br><br>
	<a href="clear-box.php" aria-label="Clear Box"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" title="Clear Box" value="Clear Box" tabindex="13" readonly /></a><br><br>
	<a href="clear-shelf.php" aria-label="Clear Shelf"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" title="Clear Shelf" value="Clear Shelf" tabindex="13" readonly /></a><br><br><br>
</div>
<?php include 'classes/footer.php'; ?>
