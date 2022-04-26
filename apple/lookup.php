<?php
include '../helpers.php';

if($_SESSION["verified"] != "v3r1f13d" && $_SESSION['verified'] != "v3r1f13d-adm1n"){
	header("Location: /inventory/apple/index.php");
}

$found = false;
$searchTerm = $_POST['searchTerm'];
$type = $_POST['type'];
if($type=="style")
	$type="vendorStyleNum";

if(isset($searchTerm)){
	include_once("../classes/db.php");
	$query = "SELECT * FROM apple_scanned WHERE $type = '$searchTerm'";
	//echo $query;
	$stock = mysqli_fetch_all($conn->query($query),MYSQLI_ASSOC); //i know, i know

	if($stock)
		$found = true;
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Item Lookup</title>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
		<link rel="stylesheet" type="text/css" media="all" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css">
		<link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="../style.css">

		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
function toggle(type){
	$('#'+type+'Lookup').toggle("fast");
	$('#'+type+'Lookup').children("input.center-block").focus();
}


</script>
<style>
	button{
		margin: 10 auto !important;
	}
	.footer-buttons{
		display:flex;
		align-items:center;
		justify-content:center;
	}
	.footer-buttons a{
		padding-right:5px;
	}
	.footer-buttons input{
		width:80px;
		margin:0 auto;
	}
	/*
	 *.formcontrol{
	 *    display:flex;
	 *    flex-direction:column;
	 *    align-items:center;
	 *    justify-content:center;
	 *}
	 */
</style>
	</head>

	<body>
		<div id="title" class="contain">
			<span class="textcenter" id="titleText">
				<h1>Item <strong>Lookup</strong></h1>
			</span>
		</div>
		<div class="contain">
			<img src="../images/UCSLogos.png">
		</div>
		<div class="contain">
			<div class="subcontainer flex-center">
				<button class="button textcenter center" onclick="toggle('sku');" style="width:140px;">Lookup by SKU</button>
				<form class="formcontrol" id="skuLookup" method="post" action="lookup.php" align="center" style="display:none;">
					<input type="hidden" name="type" value="sku">
					<input class="center-block" type="text" name="searchTerm" autofocus>
					<button type="submit">Search</button>
				</form>
			</div>
			<br>
			<div class="subcontainer flex-center">
				<button class="button textcenter center" onclick="toggle('style');" style="width:220px">Lookup by Style Number</button>
				<form class="formcontrol" id="vendorStyleNumLookup" method="post" action="lookup.php" align="center" style="display:none;">
					<input type="hidden" name="type" value="vendorStyleNum">
					<input class="center-block" type="text" name="searchTerm" autofocus>
					<button type="submit">Search</button>
				</form>
			</div>
	</div>
<!--	<div class="contain" style="width: 5%; text-align: center;">
		<a href="userPanel.php">Home</a> | 
		<a href="logout.php">Log Out</a>
</div> -->
<div class="footer-buttons">
	<a href="userPanel.php" >
		<input class="button textcenter center input-links" value="Back" tabindex="13" readonly />
	</a>
	<a href="../logout.php" >
		<input class="button textcenter center input-links" value="Logout" tabindex="13" readonly />
	</a>
</div>

<div style="display:none" id="returnedItems">
	<?php if($found){?>
	<table class="table">
		<thead>
			<th>Description</th>
			<th><?php if($_POST['type']=='sku') echo "Vendor Style Number"; else echo "SKU";?></th>
			<th>Quantity</th>
			<th>Last updated</th>
			<th>Scanned by</th>
		</thead>
		<tbody>
			<?php foreach($stock as $item): ?>
			<tr>
				<td><?php $details = getAppleDetails($_POST['searchTerm'],$_POST['type']);
					echo $details['description']."</td>
				<td>"; echo $details['column']."</td>";?>
				<td><?php echo $item['QTY'];?></td>		
				<td><?php echo $item['date'];?></td>		
				<td><?php echo getUsername($item['scanner_id']);?></td>
		</tr>
		<?php endforeach; ?>
	</table> <?php } ?>

</div>

<footer>
	<hr>
	<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
</footer>

	<script>
		whichLookup = '<?php echo $_POST['type']."Lookup";?>';
	$(document).ready(function(){
		returnedItems = $('#returnedItems');
		if(returnedItems.children().length==0){
			returnedItems = "<strong>Item not found!</strong>";
		}
		$('#'+whichLookup).parent().append(returnedItems).children('div').show(); //um, yeah. don't worry abt it
	})
</script>

	</body>

</html>

