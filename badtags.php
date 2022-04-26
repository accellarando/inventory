<?php
$head = ["title" =>"Tags to Reprint"];
$header = "Tags to Reprint";
include "classes/header.php";

/*
	session_start();
	include 'session-timeout.php';
	if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
		header("Location: http://www.info.campusstore.utah.edu/inventory/index.php");
	}
	
	echo "<html>
	<head>
	<meta charset=\"utf-8\">
	<script src=\"https://www.google.com/recaptcha/api.js\" async defer></script>
	
	<title>Tags to Reprint</title>
	<!--<link rel=\"stylesheet\" type=\"text/css\" href=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.7/themes/smoothness/jquery-ui.css\">
	<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js\"></script>
	<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js\"></script>-->
    
    <link rel=\"stylesheet\" href=\"//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css\">
	<link rel=\"stylesheet\" href=\"//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css\">
    <script src=\"https://code.jquery.com/jquery-1.12.4.js\"></script>
    <script src=\"https://code.jquery.com/ui/1.12.1/jquery-ui.js\"></script>
	<script src=\"//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js\"></script>
    
	<link rel=\"stylesheet\" type=\"text/css\" media=\"all\" href=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css\">
	<!--jquery.timepicker.min.css-->
	
	<link href=\"https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900\" rel=\"stylesheet\" type=\"text/css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\">
     
</head>

<body>
	<div id=\"title\" class=\"contain\">
		<span class=\"textcenter\" id=\"titleText\">
			<h1><strong>Tags to Reprint</strong></h1>
			</span>
 */
?>
<div class="contain">
	<table class="fancyTable center " style="width: 50%; border-spacing: 12px; margin-left: 33%;">
		<th><strong>SKU</strong></th>
		<th><strong>Quantity</strong></th>
		<th></th>
<?php	
	$query = "SELECT * FROM bad_tags;";
	$tags = $conn->query($query);
	
	while($row = $tags->fetch_assoc()):?>
		<tr>
			<td style="text-align:center;"><?php echo $row['sku']; ?></td>
			<td style="text-align:center;" ><?php echo $row['qty']; ?></td>
			<td><form action="deletebadtag.php" method="post"><button type="submit" name="submit" value="<?php echo $row['sku']; ?>">delete</button></form></td>
		</tr>

		<?php endwhile; ?>
	</table>
</div>

	<?php include "classes/footer.php"; ?>	
