<?php
	require __DIR__."/classes/phpheader.php";
	if(isset($_POST["dept"])){
		$_SESSION["dept"] = $_POST["dept"]; 
	}
	if(isset($_POST["storenum"])){
		$_SESSION["storenum"] = $_POST["storenum"];
	}
	if(isset($_POST["fixnum"])){
		$_SESSION["fixnum"] = $_POST["fixnum"];
	}
	if(isset($_POST["shelfnum"])){
		$_SESSION["shelfnum"] = $_POST["shelfnum"];
	}
	if(isset($_POST["boxnum"])){
		$_SESSION["boxnum"] = $_POST["boxnum"];
	}
	
	$query = "SELECT * FROM completed_fixtures WHERE
		department = '".$_SESSION['dept']."' AND
		store = '".$_SESSION['storenum']."' AND
		fixture = '".$_SESSION['fixnum']."';";
	$completed = mysqli_fetch_all(mysqli_query($conn,$query), MYSQLI_ASSOC);
	if(count($completed)>0){
		$_SESSION['completedArray'] = $completed;
		Header("Location: completed-confirmation.php");
	}
	
	if(isset($_POST["wontscan"])){
		//error_log("wontscan");
		$query = "SELECT * FROM bad_tags WHERE sku = '".$_POST["barcode"]."';";
		$result = $conn->query($query)->fetch_assoc();

		//If the bad_tag isn't in the database yet, add it.
		if(empty($result)){
			$query = "INSERT INTO bad_tags (sku, qty) VALUES ('".$_POST["barcode"]."', '1');";
			$conn->query($query);
		}else{ //Otherwise, adjust the quantity accordingly.
			$qty = (int)$result['qty'];
			$qty++;
			
			$query = "UPDATE bad_tags SET qty ='".$qty."' WHERE sku ='".$_POST["barcode"]."';";
			$conn->query($query);
		}
	}
	
	if(isset($_POST["barcode"])){
		$_POST["barcode"] = trim($_POST["barcode"]);
		//Appending 0s used to happen here

		//if you want. you could extend this to calculate/verify check digit. that feels a little extra though
		$barcodeValid=false;
		$validLengths = [7,8,12,13,17,18];
		if(in_array(strlen($_POST['barcode']),$validLengths))
			$barcodeValid=true;
		// if(strlen($_POST['barcode'])==17 && substr($_POST['barcode'],0,1)!=2) //only MBS typecodes can be 17; they all start with 2
		// 	$barcodeValid=false;
		//Could put more validation stuff in here.

		//Handle invalid barcode
		if(!$barcodeValid){
			//echo "Invalid Barcode!";
			$_SESSION['badCount']++;
			if($_SESSION['badCount']>2){
				$form = "<form method='post' action='scan.php'>
					<input class='center-block no-arrows' type='number' id='barcode' name='barcode' size='35' autofocus autocomplete='off'><br><br>
					<input class='button text-center' type='submit' value='Submit'>
					<input class='button text-center' type='submit' value='Bad Tag' name='wontscan'>
				</form>
				<a href='scan.php'><button class='button textcenter'>Back</button></a>";
				handleError("Please type it in manually:",$form,true);
			}
			else{
				$form = "<form method='post' action='scan.php'>
					<input class='center-block no-arrows' type='number' id='barcode' name='barcode' size='35' autofocus autocomplete='off'><br><br>
				</form>
				<a href='scan.php'><button class='button textcenter'>Back</button></a>";
				handleError("Invalid Barcode Length! Try again?",$form,true);
			}
		}

		//Handling different ISBNs
		$barcodeLength = strlen($_POST['barcode']);
		if($barcodeLength==13 || $barcodeLength==18){
			$itemClass = getItemDetails($_POST['barcode'],$_SESSION['storenum'])['class'];
			if($itemClass!=102 && $itemClass!=101)
				$_POST['barcode'] = substr($_POST['barcode'],0,13); //remove sup code - not needed, not a textbook
			else if($barcodeLength==13){ //it's a textbook. but at what cost??
				$errorJs = '
					<script>
						function editSku(suffix){
							$("#originalSku").val($("#originalSku").val()+suffix);
							$("#errorForm").submit();
						}
					</script>
					';
				$form = $errorJs;
				$form .= '<p>If you keep getting this error, make sure your scanner is configured properly.</p>
					<form method="post" action="scan.php" id="errorForm">
					<input id="originalSku" type="hidden" name="barcode" value="'.$_POST["barcode"].'">
					<input type="button" class="button text-center" onclick="editSku(\'90000\')" value="New">
					<input type="button" class="button text-center" onclick="editSku(\'99990\')" value="Used">
				</form>
				
				<a href="scan.php"><button class="button textcenter">Back</button></a>';
				handleError("Book without supplementary code detected: is it new or used?",$form,true);
			}
		}
		//Card box bs
		if($barcodeLength==17 && substr($_POST['barcode'],0,1)!=="2")
			$_POST['barcode'] = substr($_POST['barcode'],0,12);

		if(!getItemDetails($_POST['barcode'],$_SESSION['storenum'])){
			$form = "
				<form method='post' action='scan.php'>
					<input type='number' name='barcode' class='center-block no-arrows' autofocus autocomplete='off'><br><br>
					<input type='submit' value='Try Again'>
				</form>
				<form method='post' action='report-sku.php'>
					<input type='hidden' name='identifier' value='{$_POST['barcode']}-'>
					<input type='submit' value='Report invalid SKU'>
				</form>";
			handleError("SKU {$_POST['barcode']} not found in database for store {$_SESSION['storenum']}! Try again or report?",$form);
		}
			
		$infoarray = array();
		
		//grab all the other barcodes
		if(isset($_SESSION["barcode"])){
			$infoarray = $_SESSION["barcode"];
		}
		
		//gets subarray by key
		$item = $_SESSION["fixnum"] .'-'. $_SESSION["shelfnum"] .'-'. $_SESSION["boxnum"];
		$currentkey = $_POST["barcode"];
		$currentarray = array();
		if(isset($infoarray[$currentkey])){
			$currentarray = $infoarray[$currentkey];
		}
		
		//append new barcode to array of all the ones that were scanned
		$found = false;
		for($i = 0; $i < count($currentarray); $i++){
			$newitem = $currentarray[$i];
			if(substr($newitem, 0, strlen($item)) == $item){
				//adds 1 to quantity if found
				$qty = (int)(substr($newitem, strrpos($newitem, "-")+1));
				$qty++;
				
				$newitem = substr($newitem, 0, strrpos($newitem, "-")+1) . $qty;
				
				$currentarray[$i] = $newitem;
				$found = true;
				break;
			}
		}
		if(!$found){
			array_push($currentarray, $item."-1");
		}

		$infoarray[$currentkey] = $currentarray;
		
		$_SESSION["barcode"] = $infoarray;

		$_SESSION['badCount'] = 0;
	}
	
	//$elapsed=microtime(true)-$lastCheckpoint;
	//$timings.="CHECKPOINT 3: Barcode: $elapsed<br>";
	//$lastCheckpoint=microtime(true);

	$head = ['scripts'=>'<script>
		function newFixture(){
			window.location.href = "new-fixture.php";
		}

		function newShelf(){
			window.location.href = "new-shelf.php";
		}

		function newBox(){
			window.location.href = "new-box.php";
		}

		function doneScanning(){
			window.location.href = "confirm-scanned.php";
		}

		function resetUnit(data){
			if(data=="cancel"){
				$("#confirmReset").hide();
				return;
			}
			$("#confirmReset").show();
			$("#confirmReset span").text(data);
			$("#confirmReset button").attr("name", data);
		}

		function showQtyEdit(sku){
			id = $("#qtyEdit"+String(sku));
			id.toggle();
			id.children("input").attr("autofocus",true);
			if(id.is(":visible")){
				//event handler to "click" the right submit button
				id.children("input").on("keydown",function(e){
					if(e.key === "Enter"){
						e.preventDefault();
						id.children("button").click();
					}
				});
			}
			else{
				//destroy the event handler.... wait do we even need to? it\'s happening
				id.children("input").off();
			}
		}

		function clearBarcode(){
			$("#barcode").val("");
			$("#barcode").attr("disabled",false);
			$("#clearButton").hide();
		}

</script>',
		'title' => 'Scanning Page',
		'styles' => '
<style>
#success-box{
	border:0px solid;
	border-radius:7px;
	padding:10px;
	display:none;
}
.success{
	background-color:#d4edda;
	color:#155724;
	border-color:#c3e6cb;
}
.info{
	color: #383d41;
	background-color: #e2e3e5;
	border-color: #d6d8db;
}
</style>
	'];
	$header = '<strong>Scanning</strong> Page';
	include 'classes/header.php';
?>
	<?php 
	echo "<div class=\"contain\" style=\"text-align: center;\">Currently Scanning:<br><strong>Fixture:</strong> ".$_SESSION['fixnum']."&nbsp;&nbsp;&nbsp;&nbsp;<strong>Shelf:</strong> ".$_SESSION['shelfnum']."&nbsp;&nbsp;&nbsp;&nbsp;<strong>Box:</strong> ".$_SESSION['boxnum']."</div>";
	if(isset($_POST['barcode'])){?>
	<!--	<div class="contain" id="success-box">
			<strong>Success!</strong><br>
			<?php echo $_POST['barcode']."<br>";
				echo getItemDetails($_POST['barcode'],$_SESSION['storenum'])['description'];
			?><br>

			Quantity: <?php echo $qty; ?>
	</div>-->
<?php } ?>
	<div class="contain">
		<form align="center" method="post" action="scan.php">
			<!--<p>Click inside the textbox, then scan the Barcode.</p>-->
			<input class="center-block no-arrows" type="number" id="barcode" name="barcode" size="35" autofocus autocomplete='off' title="Scan or type barcode">
			<button type="button" id="clearButton" onclick="clearBarcode();" style="display:none;">Clear</button><br><br>
			<label>Won't Scan <input class="" type="checkbox" id="wontscan" name="wontscan"></label>
			<!-- add buttons for new fixture, new shelf, new box next box, and done scanning -->
			<button type="submit" name="submit" style="display: none;">Submit</button><br><br>
			<button type="button" name="newfixture" onclick="newFixture()">New fixture</button>
			<button type="button" name="newshelf" onclick="newShelf()">New shelf</button>
			<button type="button" name="newbox" onclick="newBox()">New box</button>
			<button type="button" name="done" onclick="doneScanning()">Done scanning</button>
			<button type="button" name="fixtComplete" onclick="window.location.href='fixture-complete.php'">Mark Fixture Complete</button>
		</form>
	</div><!-- end contain -->
	<div class="contain" style="height:100%;"><!-- style="height:<?php echo count($_SESSION['barcode'])*35;?>px;"> -->
		<?php	if(isset($_POST['barcode'])){?>
			<div id="success-box" class="success">
					<strong>Success!</strong><br>
					<?php echo $_POST['barcode']."<br>";
						$details = getItemDetails($_POST['barcode'],$_SESSION['storenum']);
						echo $details['description']." - $".$details['price']; 
					?><br>
					Quantity: <strong><?php echo isset($qty)?$qty:1; ?></strong>
					<form action="scan-buttons.php" method="post" >
						<?php $sku=$_POST['barcode'];?>
						<input type="hidden" name="identifier" value="<?php echo "$sku-{$_SESSION["fixnum"]}-{$_SESSION["shelfnum"]}-{$_SESSION["boxnum"]}-"?>">
						<input type="submit" name="remove" value="remove">&nbsp;
						<input type="button" onclick="showQtyEdit('A<?php echo $sku;?>');" value="update quantity">
						<span id="qtyEditA<?php echo $sku;?>" class="hide-onload" style="display:inline-block;">
							<br>
							Enter new quantity:
							<input type="number" inputmode="numeric" name="newQuantity" id="newQuantity<?php echo $sku;?>">
							<button type="submit" name="update" value="<?php echo "$sku-{$_SESSION["fixnum"]}-{$_SESSION["shelfnum"]}-{$_SESSION["boxnum"]}-"?>">update</button> &nbsp;
						</span>
					</form>
					<form action="report-sku.php" method="POST">
						<input type="hidden" name="identifier" value="<?php echo "$sku-{$_SESSION["fixnum"]}-{$_SESSION["shelfnum"]}-{$_SESSION["boxnum"]}-"?>">
						<input type="submit" name="report" value="report incorrect data">
					</form>

			</div>
		<?php } ?>

<div style="display:grid; grid-template-columns: repeat(5,auto) 250px; align-items:top; row-gap:10px;">
	<?php
		$descriptions = array();
		if(isset($_SESSION['descriptions'])){
			$descriptions = $_SESSION['descriptions'];
		}
	
		//print all the scanned data
		if(isset($_SESSION['barcode'])){
		$data = $_SESSION["barcode"];
		$keys = array_keys($data);
		$descriptionKeys = array_keys($descriptions);
		
		//combine duplicate barcodes while printing data
		foreach($keys as $key){
			//get the array of fixture,box,shelf info with this barcode
			$currentarray = $data[$key];
			//$desc = null;

			$sku = $key;

			if(in_array($key, $descriptionKeys)){
				$desc = $descriptions[$key];
			}else{
				$desc = getItemDetails($key,$_SESSION['storenum'])['description']; //defined in helpers.php 
				
				$descriptions[$key] = $desc;
			}

			$qty = 0;
			foreach($currentarray as $datastring){
				$additionalQty = (int)(substr($datastring, strrpos($datastring, "-")+1));
				$qty = $qty + $additionalQty;
			}
			//update this for quantities of the same barcode
?>

<form action="scan-buttons.php" method="post" style="display:contents;"><!-- style="grid-row-start">-->
	<span ><strong ><?php echo $qty; ?></strong></span>
	<span > - </span>
	<span> <?php echo $sku; ?></span>
	<span> - </span>																	   
	<span><?php echo $desc;?></span>
	<div style="display:grid; grid-template-columns: 90px repeat(3,auto); margin-top:0px; grid-template-rows: repeat(3,auto); column-gap:10px;"> <!-- love this for us -->
		<input type="hidden" name="identifier" value="<?php echo "$sku-{$_SESSION["fixnum"]}-{$_SESSION["shelfnum"]}-{$_SESSION["boxnum"]}-"?>">
		<!--<button type="submit" name="remove" value="remove">remove</button> &nbsp;-->
		<input type="submit" name="remove" value="remove" style="grid-column: 1 / 2">
		<input type="button" onclick="showQtyEdit('<?php echo $sku;?>');" value="update quantity" style="grid-column: 2 / 5">
		<span id="qtyEdit<?php echo $sku;?>" class="hide-onload" style="display:contents">
			<br>
			<span style="grid-row: 2 / 3; grid-column: 1 / 5">Enter new quantity:</span>
			<input type="number" inputmode="numeric" name="newQuantity" id="newQuantity<?php echo $sku;?>" style="grid-column: 1 / 4">
			<button type="submit" name="update" value="<?php echo "$sku-{$_SESSION["fixnum"]}-{$_SESSION["shelfnum"]}-{$_SESSION["boxnum"]}-"?>" style="grid-column: 4 / 5">update</button>
		</span>
<br>
	</div>
</form>


<?php $_SESSION['descriptions'] = $descriptions;
		}}
?>

<br>
</div>
	</div>

	<div class="contain">
		<form align="center" method="post" action="reset.php">
			<button type="button" name="fixture" onclick="resetUnit('fixture')">Reset fixture</button>
			<button type="button" name="shelf" onclick="resetUnit('shelf')">Reset shelf</button>
			<button type="button" name="box" onclick="resetUnit('box')">Reset box</button>
			<div id="confirmReset" style="display:none;">
				<p>Are you sure you would like to reset the <span>UNIT</span>?</p>
				<button type="submit" name="">Yes</button>
				<button type="button" onclick="resetUnit('cancel')">Cancel</button>
			</div>
		</form>
	</div>

		<script>
		$(document).ready(function(){
			$(".hide-onload").hide();
			$('#barcode').on("keyup",function(e){
				$('#clearButton').show();
				if(e.key === "Enter")
					$('#barcode').attr('disabled',true);
			});
			$('#success-box').fadeIn('medium').delay(1500).animate({color:'#383d41',backgroundColor:'#e2e3e5;'},'slow');
			//setTimeout(function(){$('#success-box').addClass('info').removeClass('success');},2000);
		});
		</script>
<?php include 'classes/footer.php'; 
?>
