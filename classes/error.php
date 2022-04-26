<?php
$head = ['title'=>'Error!'];
$header = "<strong>An error occurred.</strong>";
include_once 'classes/header.php';
?>

<div class="contain textcenter">
	<h2>Error: <?php echo $error; ?></h2>
	<?php echo $actions; ?>
</div>

<?php
include 'classes/footer.php'; 
if($fatal){
	die();
}
?>
