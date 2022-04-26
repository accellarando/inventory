<?php 
session_start();
include_once 'session-timeout.php';

$backButton = "<button onclick='window.history.back();'>Back</button>";

//You create this file - set up a mysql connection called $conn
include_once("db.php");

function getItemDetails($sku,$store = null){
	error_reporting(0); //so it doesn't mess up the spreadsheet with Notices if barcode not in db.
	//Normally this isn't an issue - just in my dev environment.
	global $conn;

	//$processedSku = trimCheckDigit(cleanSku($sku)); 
	$processedSkus = trimCheckDigit($sku); 
	$originalSku = $sku;

	$description='';
	$price='';

	//There's a FULLTEXT index on the SKU column, which has been reversed.
	//(This is to get the performance benefits - we care about the last
	//part of the string, not the prepended 0s)
	foreach($processedSkus as $sku){
		$sku=strrev($sku);
		//$query = "SELECT * FROM fromfile WHERE sku LIKE '%$sku'";
		$query = "SELECT * FROM fromfile
			WHERE MATCH (SKU)
			AGAINST ('$sku*' IN BOOLEAN MODE)";
		if(isset($store))
			 $query .= " AND storeNumber='$store'";
		//error_log($query);
		$result = $conn->query($query)->fetch_assoc();
		$description = $result['description'];
		$priceColumn='retailPrice';
		if(strlen($originalSku)==18){
			$suffix=substr($originalSku,13);
			if($suffix=="99990"){
				$priceColumn = 'usedPrice';
			}
		}
		$price = number_format((double)$result[$priceColumn],2);
		$class = $result['classCode'];
		if(isset($result) && count($result)>0)
			return ['description' => $description,
				'price' => $price,
				'class' => $class];
			//array($description,$price);
	}
	//you shouldn't really reach this point
	//if you're here, it probably means the sku isn't in fromfile
	//error_log(addslashes("Inventory: getItemDetails failed for sku $sku at store $store"));
	//return ['description' => "NOT AVAILABLE", 'price'=>'N/A'];
	return;
}

function getUsername($id){
	global $conn;
	$query = "SELECT name FROM users WHERE id=$id";
	$result=mysqli_fetch_assoc(mysqli_query($conn,$query));
	if($result==NULL)
		return "Unknown";
	return $result['name'];
}

function trimCheckDigit($sku){
	$possibleSkus = array();

	$skuLength = strlen($sku);

	//If it has 7 digits, go right ahead. No processing needed. (Code 39, no check digit, no codes appended)
	if($skuLength==7)
		array_push($possibleSkus, $sku);

	//Book with supplementary code
	if($skuLength==18) {
		array_push($possibleSkus, substr($sku,4,8));
		array_push($possibleSkus, substr($sku,0,12));
	}

	//Most 17 digits are MBS Code-39 barcodes.
	//A couple are Hallmark box sets with weird sup codes. Check.
	if($skuLength==17){
		if(substr($sku,0,1)==="2") //mbs
			array_push($possibleSkus, substr($sku,0,7));
		else //sup code trim
			array_push($possibleSkus, substr($sku,0,11));
	}
		
	//If it has 13 digits, it's an EAN-13 or a book. Let's try a few possibilities.
	if($skuLength==13){
		//Book barcode - just the important bits
		array_push($possibleSkus, substr($sku,4,8));
		array_push($possibleSkus, substr($sku,0,12));
	}
	
	//If it has 12 or 8 digits, remove the last digit - that's a check digit, it's not in the db.
	if($skuLength==12 || $skuLength==8)
		array_push($possibleSkus,substr($sku,0,($skuLength-1)));

	return $possibleSkus;  
}

/* Leaves check digit intact, but removes any leading 0s. (legacy, phasing out of use)
 * Also leaves on type codes, etc.
 * For printing out a scannable barcode.
 */
function cleanSku($sku){
	//var_dump($sku);
	$skuLength = strlen($sku);

	//If it has 7 digits, go right ahead. No processing needed. (Code 39, no check digit, no codes appended)
	if($skuLength==7)
		return $sku;

	//We need to remove at least 5 digits so we'll start with that.
	if($skuLength==22){
		$sku = substr($sku,5,17);
		if(((string)$sku)[0]=='2')
			return $sku;
		//Otherwise it's gonna be a 0.
		//Next possibility is EAN-13 (13 digits), so strip first 4
		$sku = substr($sku,4,13);
		$skuLength = 13;
	}

	if($skuLength==13){
		//If 0, this is a UPC.
		if(((string)$sku)[0]==0){
			$sku = substr($sku,1,12); //if it's a 0, that's a UPC or someth. otherwise we're an EAN and we're good
			$skuLength = 12; //if I did this right..? 
		}
		else
			return $sku;
	}

	if($skuLength==12){
		//Now. It's 12 digits.
		// If it's MBS, return the relevant part. 
		// If it's got 0000[8 digits], return those 8 digits.
		// Otherwise, just return $sku.

		//verify MBS/code 39
		$prefix = substr($sku,0,5);
		$midrange = (float)substr($sku,5,3);
		//echo $midrange."<br>";
		if(((int)$prefix==0) && (200 <= $midrange && 279 >= $midrange))
			return substr($sku,5,7);

		$prefix = substr($sku,0,4);
		if((int)$prefix == 0)
			return substr($sku,3,8);
	}

	return $sku;
}

/*For apple inventory.
 * Given your searchKey for a column (sku or vendorStyleNum),
 *	get the value of the other column, and the item's description.
 */
function getAppleDetails($searchKey, $column){
	global $conn;
	$oppositeColumn = ($column=='sku') ? 'vendorStyleNum' : 'sku';
	$query = "SELECT description, $oppositeColumn AS `column` FROM apple WHERE $column = '$searchKey'";
	//echo $query;
	$result = mysqli_fetch_all(mysqli_query($conn,$query),MYSQLI_ASSOC);
	return $result[0];
}

function handleError($error, $actions=NULL, $fatal=true){
	error_log("Inventory: ".$_SERVER['PHP_SELF'].": $error");
	if(!isset($actions)){
		global $backButton;
		$actions = $backButton; 
	}
	require(__DIR__.'/error.php');
}

//calculateCheckDigit moved to rely on Picqer

function removeScanningData(){
	unset($_SESSION["barcode"]);
	unset($_SESSION['dept']);
	unset($_SESSION['storenum']);
	unset($_SESSION['fixnum']);
	unset($_SESSION['shelfnum']);
	unset($_SESSION['boxnum']);
	unset($_SESSION['badCount']);
	unset($_SESSION['descriptions']);
}

?>
