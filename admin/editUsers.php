<?php
$admin = true;
$head = ['title'=>'Edit Users',
	'scripts'=>"	<script>
		function updateSubmission(id){
			$('#idArray').val($('#idArray').val()+','+id);
			$('#usernameArray').val($('#usernameArray').val()+','+$('#username'+id).val());
			$('#nameArray').val($('#nameArray').val()+','+$('#name'+id).val());
			$('#clearanceArray').val($('#clearanceArray').val()+','+$('#clearance'+id).val());
		}
	</script>"];
$header = "<strong>Edit</strong> Users";
include '../classes/header.php';

$query = "SELECT * FROM users;";
$cashiers = $conn->query($query)->fetch_all(MYSQLI_ASSOC);
?>
<div class="contain">
	<table class="fancyTable center" style="width: 80%; border-spacing: 12px; margin-left: 13%;">
		<th><strong>Username</strong></th>
		<th><strong>Name</strong></th>
		<th><strong>Clearance</strong></th>
		<?php foreach($cashiers as $cashier): ?>
		<tr> 
			<td style="text-align:center"><input onchange="updateSubmission(<?php echo $cashier['id']; ?>)" type="text" name="username" id="username<?php echo $cashier['id']; ?>" value="<?php echo $cashier['username'];?>"></td>
			<td style="text-align:center"><input onchange="updateSubmission(<?php echo $cashier['id']; ?>)" type="text" name="name" id="name<?php echo $cashier['id']; ?>" value="<?php echo $cashier['name'];?>"></td>
			<td style="text-align:center">
				<select onchange="updateSubmission(<?php echo $cashier['id']; ?>)" name="clearance.<?php echo $cashier['id']; ?>" id="clearance<?php echo $cashier['id']; ?>">
					<option value="0" <?php if($cashier['clearance']==0) echo 'selected'; ?>> 0 User </option>
					<option value="1" <?php if($cashier['clearance']==1) echo 'selected'; ?>> 1 Manager </option>
					<option value="2" <?php if($cashier['clearance']==2) echo 'selected'; ?>> 2 Administrator </option>
				</select>
			</td>
		</tr>
		<?php endforeach; ?>

	</table>

	<form method="post" action="finishEdit.php">
		<input type="hidden" name="ids" id="idArray">
		<input type="hidden" name="usernames" id="usernameArray">
		<input type="hidden" name="names" id="nameArray">
		<input type="hidden" name="clearances" id="clearanceArray">
		<input class="button textcenter center" style="width:150px; margin: 0 auto;"  type="submit" value="Submit Changes">
	</form>

</div>
<?php include '../classes/footer.php'; ?>
