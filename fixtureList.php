<?php
$head = ['title'=>"Fixture List"];
include 'classes/header.php';
?>

<div class="contain">
	<form action="showFixtures.php" method="POST">
		<?php include 'classes/departmentSelector.html'?>
		<?php include 'classes/storeSelector.html'?>
		<input type="Submit" name="submit" value="Look Up Fixtures">
	</form>
</div>

<?php include 'classes/footer.php'; ?>
