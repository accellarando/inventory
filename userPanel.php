<?php
$head = ["title" => "User Panel"];
$header = "<strong>User</strong> Panel";
include 'classes/header.php';
?>

<?php
	if($_SESSION["verified"]=="v3r1f13d-adm1n"){?>
		<div class="contain">
			<a href="admin/adminPanel.php" aria-label="Manager Panel"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" title="Manager Panel" value="Manager Panel" tabindex="13" readonly /></a>
		</div>
<?php } ?>
	<div class="contain" >
		<br><br>
		<a href="scanning.php" aria-label="Scan" ><input title="Scan"class="button textcenter center" style="width: 180px; margin: 0 auto;" value="Scan" tabindex="13" readonly /></a><br><br>
		<a href="printing.php" aria-label="Print or Email"><input title="Print or Email"class="button textcenter center" style="width: 180px; margin: 0 auto;" value="Print or Email" tabindex="13" readonly /></a><br><br>
		<a href="removing.php" aria-label="Edit/Remove Scanned Data" ><input title="Edit/Remove Scanned Data" class="button textcenter center" style="width: 180px; margin: 0 auto;" value="Edit/Remove Scanned Data" tabindex="13" readonly /></a><br><br>
		<a href="badtags.php" aria-label="Tags to Reprint"><input title="Tags to Reprint" class="button textcenter center" style="width: 180px; margin: 0 auto;" value="Tags to Reprint" tabindex="13" readonly /></a><br><br>
		<a href="lookup.php" aria-label="Item Lookup"><input title="Item Lookup" class="button textcenter center" style="width: 180px; margin: 0 auto;" value="Item Lookup" tabindex="13" readonly /></a><br><br>
		<a href="fixtureList.php" aria-label="View Fixtures"><input title="View Fixtures" class="button textcenter center" style="width: 180px; margin: 0 auto;" value="View Fixtures" tabindex="13" readonly /></a><br><br>
		<!--<a href="scannerCodes.php"><input class="button textcenter center" style="width: 130px; margin: 0 auto;" value="Fix Scanner" tabindex="13" readonly /></a><br><br>-->
		<br>
	</div>
	<?php include 'classes/footer.php'; ?>
