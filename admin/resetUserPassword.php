<?php
$admin = true;
include '../classes/phpheader.php';

$incorrect = false;
$updated = false;

$users = mysqli_fetch_all(mysqli_query($conn,"SELECT * FROM users"),MYSQLI_ASSOC);

if(isset($_POST['password'])){
	if($_POST["password"] == $_POST["reppassword"]){
		if(!$conn){
			//error messages
			echo "Unable to connect to database! <br>";
			echo "errno - " . mysqli_connect_errno()."<br>";
			echo "error - " . mysqli_connect_error()."<br>";
			die();
		}

		$query = "SELECT 1 FROM users WHERE username = '".$_POST['username']."'";
		if(mysqli_num_rows(mysqli_query($conn,$query))<1)
			die("<h2> User not in database - did you type in correct unid?</h2>");


		$query = "UPDATE users SET password = '".sha1($_POST["password"])."' WHERE username = '".$_POST['username']."';";
		$conn -> query($query);

		$query = "UPDATE users SET temp_password = '1' WHERE username = '".$_POST['username']."';";
		$conn -> query($query);

		$updated = true;
	}else{
		$incorrect = true;
	}
}
$head = ['title'=>'Reset Password'];
$header = "<strong>Reset</strong> Password";
include '../classes/header.php';

if($incorrect){
	echo "<div class=\"contain\"><p style=\"color: red; font-size: 17px;\">The passwords do not match.</p></div>";
}
if($updated){
	echo "<div class=\"contain\"><p style=\"color: green; font-size: 17px;\">The password has been updated.</div>";
}
?>
<div class="contain">
	<form action="resetUserPassword.php" method="post" enctype="multipart/form-data">
		<div style="width: 50%; margin: 0 auto;"><label for="username">Username: </label><input type="text" name="username" id="username" required><br></div><br>
		<div style="width: 60%; margin: 0 auto;"><label for="password">Temp Password: </label><input type="password" name="password" required><br></div><br>
		<div style="width: 60%; margin: 0 auto;"><label for="reppassword">Repeat Password: </label><input type="password" name="reppassword" required><br></div>
		<div class="subForm">
			<div class="centerButton"><button type="submit" name="submit" value="Submit">Submit</button></div>
		</div>
	</form>
</div>
<div class="contain">
	<p>Lookup uNID</p>
	<input type="text" id="unidLookup" onkeyup="lookup()" placeholder="Start typing..."><br>
	<table class="table">
		<thead>
			<th>Name</th>
			<th>Username</th>
		</thead>
		<tbody id="userTable">
			<?php foreach($users as $user): ?>
			<tr style="display:none;">
				<td class="name"><?php echo $user['name'];?></td>
				<td class="username" onclick="autofill(this)" style="cursor:pointer;"><?php echo $user['username'];?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p>Hint: click a username to fill it in.</p>
</div>

<script>
function lookup(){
	names = $('#userTable').children().children(".name");
	searchTerm = $('#unidLookup').val();
	names.each(function(index){
		if(this.innerHTML.toLowerCase().search(searchTerm)>-1){ //if this name is found in the input term:
			$(this).parent().show();
		}
		else{
			$(this).parent().hide();
		}
	});
	if(searchTerm==""){
		names.parent().hide();
	}
}
function autofill(e){
	unid = e.innerHTML;
	$('#username').val(unid);
}
</script>

<?php include '../classes/footer.php'; ?>
