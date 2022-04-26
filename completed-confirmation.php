<?php

include "classes/helpers.php";

if(!empty($_POST)){
	if(isset($_POST['clear'])){
		//clear the fixture
		$query = "DELETE FROM scanned 
			WHERE fixtureNum = '".$_SESSION['fixnum']."' AND
			store = '".$_SESSION['storenum']."' AND
			dept = '".$_SESSION['dept']."';";
		//echo $query;
		if(mysqli_query($conn,$query)){
			//remove the entries from completed_fixtures
			$query = "DELETE FROM completed_fixtures 
				WHERE fixture = '".$_SESSION['fixnum']."' AND
				store = '".$_SESSION['storenum']."' AND
				department = '".$_SESSION['dept']."';" ;
			if(mysqli_query($conn,$query))
				Header("Location: scan.php");
			else
				echo "MySQL error: ".mysqli_error($conn);
		}
		else
			echo "MySQL error: ".mysqli_error($conn);
	}
	else{
		//remove the entries from completed_fixtures
		$query = "DELETE FROM completed_fixtures 
			WHERE fixture = '".$_SESSION['fixnum']."' AND
			store = '".$_SESSION['storenum']."' AND
			department = '".$_SESSION['dept']."';";
		if(mysqli_query($conn,$query))
			Header("Location: scan.php");
		else
			echo "MySQL error: ".mysqli_error($conn);
	}
}

$head = ['title'=>"Fixture Already Completed"];
$header = "Already <strong>Completed</strong>";
include 'classes/header.php';
?>
<div class="contain">
	<h2>This fixture has already been marked complete.</h2>
	FIXTURE: Department <?php echo $_SESSION['completedArray'][0]['department']; ?>, store <?php echo $_SESSION['completedArray'][0]['store']; ?>, fixture <?php echo $_SESSION['completedArray'][0]['fixture']; ?>.
	<br>
	Details:
	<table class="table">
		<tr>
			<th>Completed</th>
			<th>User</th>
			<th>Comments</th>
		</tr>
		<?php foreach($_SESSION['completedArray'] as $completed): ?>
		<tr>
			<td><?php echo $completed['time_completed']; ?></td>
			<td><?php echo getUsername($completed['scanner_id']); ?></td>
			<td><?php echo $completed['comments']; ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<div class="contain">
	<h2> How do you want to continue? </h2>
	<form method="POST" action="completed-confirmation.php" class="textcenter center">
		<input type="submit" class="button" name="append" style="width:100px;" title="Add to fixture" value="Add to fixture">
		<input type="submit" class="button" name="clear" style="width:90px;" title="Clear fixture" value="Clear fixture">
	</form>
</div>

<a href="scanning.php">
	<input class="button textcenter center" style="width: 140px; margin: 0 auto;" value="Cancel - go back" tabindex="13" readonly />
</a>

<?php include 'classes/footer.php'; ?>
