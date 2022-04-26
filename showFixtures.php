<?php
include 'classes/phpheader.php';
$query = "SELECT fixtureNum, CAST(fixtureNum AS unsigned) AS sortCol FROM scanned  
	WHERE store='".mysqli_real_escape_string($conn,$_POST['storenum'])."' AND dept='".mysqli_real_escape_string($conn,$_POST['dept'])."'
	GROUP BY(fixtureNum)
	ORDER BY sortCol ASC";
$fixtures = array_column(mysqli_fetch_all(mysqli_query($conn,$query),MYSQLI_ASSOC),"fixtureNum"); 

$head = ['title'=>'View Fixtures'];
include 'classes/header.php';
?>
<div class="contain textcenter">
Department: <?php echo htmlentities($_POST['dept']);?><br>
	Store: <?php echo htmlentities($_POST['storenum']);?>
</div>
<div class="contain">

<table class='table'>
	<thead>
		<th>Fixture</th>
		<th>Shelves</th>
		<th>Boxes</th>
		<th>Modified</th>
		<th>Actions</th>
	</thead>
	<tbody>
		<?php foreach($fixtures as $fixture):
			$query = "SELECT date FROM scanned WHERE fixtureNum='$fixture' ORDER BY date DESC";
			$date = mysqli_fetch_row(mysqli_query($conn,$query))[0];

			$query = "SELECT DISTINCT shelfNum
				FROM scanned WHERE fixtureNum = '".$fixture."' ORDER BY CAST(shelfNum AS unsigned)  ASC";
			$shelves = mysqli_fetch_all(mysqli_query($conn,$query),MYSQLI_ASSOC);
			?>
			<tr>
				<td><?php echo $fixture;?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><?php echo $date;?></td> 
				<td><button type="button" onclick="submitForm({action: 'print', fixture: '<?php echo $fixture; ?>'});">Print</button>
					<button type="button" onclick="submitForm({action: 'edit', fixture: '<?php echo $fixture; ?>'});">Edit</button>
				</td>
			</tr>
			<?php
			foreach($shelves as $shelf): 
				$query = "SELECT date FROM scanned WHERE fixtureNum='$fixture' AND shelfNum='{$shelf['shelfNum']}' ORDER BY date DESC";
				$date = mysqli_fetch_row(mysqli_query($conn,$query))[0];

				$query = "SELECT DISTINCT boxnum 
					FROM scanned WHERE fixtureNum='$fixture' AND shelfNum='{$shelf['shelfNum']}' ORDER BY CAST(boxnum AS unsigned) ASC";
				$boxes = mysqli_fetch_all(mysqli_query($conn,$query),MYSQLI_ASSOC);
				if($shelf['shelfNum']!=''):?>
					<tr>
						<td style="border:none;">&nbsp;</td>
						<td><?php echo $shelf['shelfNum'];?></td>
						<td>&nbsp;</td>
						<td><?php echo $date; ?></td>
						<td>
							<button type="button" onclick="submitForm({action: 'print', fixture: '<?php echo $fixture; ?>', shelf: '<?php echo $shelf['shelfNum'];?>'});">Print</button>
							<button type="button" onclick="submitForm({action: 'edit', fixture: '<?php echo $fixture; ?>', shelf: '<?php echo $shelf['shelfNum'];?>'});">Edit</button>
						</td>
					</tr>
				<?php endif; foreach($boxes as $box): 
					$query = "SELECT date FROM scanned WHERE fixtureNum='$fixture' AND shelfNum='{$shelf['shelfNum']}' AND boxnum='{$box['boxnum']}' ORDER BY date DESC";
					$date = mysqli_fetch_row(mysqli_query($conn,$query))[0];
					if($box['boxnum']!=''):
				?>
					<tr>
						<td style="border:none;">&nbsp;</td>
						<td style="border:none;">&nbsp;</td>
						<td><?php echo $box['boxnum'];?></td>
						<td><?php echo $date; ?></td>
						<td>
							<button type="button" onclick="submitForm({action: 'print', 
												  fixture: '<?php echo $fixture; ?>',
												  shelf: '<?php echo $shelf['shelfNum'];?>',
												  box: '<?php echo $box['boxnum']; ?>'});">Print</button>
							<button type="button" onclick="submitForm({action: 'edit', 
												  fixture: '<?php echo $fixture; ?>',
												  shelf: '<?php echo $shelf['shelfNum'];?>',
												  box: '<?php echo $box['boxnum']; ?>'});">Edit</button>

						</td>
					</tr>
				<?php endif; endforeach;?>
			<?php endforeach;?>
		<?php endforeach;?>
	</tbody>
</table>

</div>

<form method="POST" id="postForm">
	<input type="hidden" name='dept' value='<?php echo htmlentities($_POST['dept']);?>'>
	<input type="hidden" name='store' value='<?php echo htmlentities($_POST['storenum']);?>'>
	<input type="hidden" name='fixture' value=''>
	<input type="hidden" name='shelf' value=''>
	<input type="hidden" name='box' value=''>
</form>

<script>
	function submitForm(args){
		//Set form action
		if(args.action === 'print')
			action = 'printing.php';
		if(args.action === 'edit')
			action = 'remove-scan.php';
		$('#postForm').attr('action',action);

		//Set values
		names = ['fixture','shelf','box'];
		names.forEach((element) => {
			if(args.hasOwnProperty(element)){
				$('[name='+element+']').attr('value',args[element]);
			}
		});

		//Submit form
		$('#postForm').submit();
	}

</script>

<?php include 'classes/footer.php'; ?>
