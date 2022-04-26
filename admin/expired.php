<?php
$admin = true;
$head = ['title'=>'Expired Users'];
$header = "<strong>Expired</strong> Users";
include '../classes/header.php';
?>
<div class="contain">
	<table class="fancyTable center " style="width: 80%; border-spacing: 12px; margin-left: 13%;">
		<th><strong>Username</strong></th>
		<th ><strong>Name</strong></th>
		<th ><strong>Last Login</strong></th>
		<th><strong>Clearance</strong></th>
	
<?php	
	$query = "SELECT * FROM users WHERE last_login < '".$_POST['date']."' AND clearance < 2;";
	$cashiers = $conn->query($query);
	
	while($row = $cashiers->fetch_assoc()){
		$clearance = "user";
		if((int)$row['clearance'] > 0){
			$clearance = 'manager';
		}
		if((int)$row['clearance'] > 1){
			$clearance = 'admin';
		}?>	
		<tr>
			<td style="text-align:center;"><?php echo $row['username'];?></td>
			<td style="text-align:center;"><?php echo $row['name'];?></td>
			<td style="text-align:center;"><?php echo substr($row['last_login'],0, 10);?></td>
			<td style="text-align:center;"><?php echo $clearance; ?></td>
		</tr>
		<?php	}?>
	</table>
</div>
<?php include '../classes/footer.php'; ?>
