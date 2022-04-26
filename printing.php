<?php
	include 'classes/helpers.php';
	
	$query = "SELECT * FROM users WHERE clearance=1";
	$managers = mysqli_fetch_all(mysqli_query($conn, $query), MYSQLI_ASSOC);
	
	$query = "SELECT manager_id FROM users WHERE username='".$_SESSION['username']."'";
	$myManager = mysqli_fetch_array(mysqli_query($conn, $query));
	$myManager = $myManager[0];

	$dept = isset($_POST['dept']) ? $_POST['dept'] : 0;
	$store = isset($_POST['store']) ? $_POST['store'] : 0;

	$head = ['scripts' => '<script> 
		//Processes the <select>s for dept and store, assuming we came from import-scanned-data.php.
		$(window).on("load", function(){
			selectedDept ='.$dept.';
			selectedStore = '.$store.';

			//get the "dept" select
			deptOptions = $("#dept").children("option");
			//iterate through options
			for(var i = 0; i<deptOptions.length; i++){
				if($(deptOptions[i]).val()==selectedDept)
					//if option value = selectedDept, add "selected
					$(deptOptions[i]).prop("selected","selected");
			}

			//repeat for store
			storeOptions = $("#storenum").children("option");
			for(var i = 0; i<storeOptions.length; i++){
				if($(storeOptions[i]).val()==selectedStore)
					$(storeOptions[i]).prop("selected","selected");
			}

		});
</script>',
				'title'=>'Printing Page'];

$header="<strong>Printing</strong> Page";
include 'classes/header.php';
?> 
		<div class="contain">
		<form name="postForm" id="postForm" action="/inventory/print.php" method="post">
			<div style="margin: 0 auto; padding-top: 10px; width: 70%;">
				<?php include 'classes/departmentSelector.html'; include 'classes/storeSelector.html'; ?>
			</div><br>
			<div style="margin: 0 auto; padding-top: 10px; width: 85%;">
				<label><strong>Fixture #:</strong>
					<input type="text" size="10" id="fixnum" name="fixnum" tabindex="2" value="<?php if(isset($_POST['fixture'])) echo $_POST['fixture']; ?>">
				</label>
				<label><strong style="margin-left: 20px;"> Shelf #:</strong>
					<input type="text" size="10" id="shelfnum" name="shelfnum" tabindex="2" value="<?php if(isset($_POST['shelf'])) echo $_POST['shelf']; ?>">
				</label>
				<label><strong style="margin-left: 20px;"> Box #:</strong>
					<input type="text" size="4" id="boxnum" name="boxnum" tabindex="2" value="<?php if(isset($_POST['box'])) echo $_POST['box']; ?>">
				</label>
			</div><br>
			<div style="margin: 0 auto; padding-top: 10px; width: 85%;">
				<span>
					<input type="checkbox" checked name="download" title="Download"><strong>Download as:</strong>
					<select name="type">
						<option value="1">PDF</option>
						<option value="2">XLSX</option>
					</select>
				</span>
				<span style="padding-left:50px;">
					<input type="checkbox" name="email" title="Email"><strong>Email to:</strong>
					<select name="emailDest">
						<?php foreach($managers as $manager): ?>
							<option value="<?php echo $manager['id']."\" ";
								if((string)$manager['id']==$myManager)
									echo "selected";
									?>><?php echo $manager['name']; ?></option>
						<?php endforeach; ?>
						<option value="-1">Michael Wahls</option>
					</select>
				</span>
			</div><br>

			<div class='textcenter'>
				<label><input type="checkbox" name="pagination"><strong>Separate by:</strong></label>
				<select name="paginationOptions">
					<option value="fixture">Fixture</option>
					<option value="shelf">Shelf</option>
					<option value="box">Box</option>
				</select>
			</div>

			<div class="subForm">
				<span>
					<input class="button textcenter" type="submit" id="submit" value="Submit" tabindex="13" readonly />
				</span>
			</div>
		</form>
		</div>
		<?php include 'classes/footer.php'; ?>
