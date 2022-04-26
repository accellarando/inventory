<?php
	session_start();
	include 'classes/session-timeout.php';
	
	if($_SESSION["verified"] != "v3r1f13d" && $_SESSION["verified"] != "v3r1f13d-adm1n"){
		header("Location: /inventory/index.php");
		exit();
	}

	//Determine which button was pressed on the previous form
	$unit = array_keys($_POST)[0];

	$fixture = $_SESSION['fixnum'];
	$shelf = $_SESSION['shelfnum'];
	$box = $_SESSION['boxnum'];

	//Grabs the data from SESSION, and indexes them numerically.
	$infoarray = $_SESSION['barcode'];
	$barcodes = array_keys($infoarray);

	//Iterate through each barcode
	foreach($barcodes as $barcode){
		//Get the fix-shelf-box-qt counts for this barcode
		$countsList = $infoarray[$barcode];
		for($i=0;$i<count($countsList);$i++){
			$split = explode("-",$countsList[$i]);

			if($unit=="fixture"){
				if($fixture==$split[0]){
					unset($infoarray[$barcode][$i]);
					continue;
				}
			}
			if($unit=="shelf"){
				//Note: fixture has to match too
				if($fixture==$split[0]){
					if($shelf==$split[1]){
						unset($infoarray[$barcode][$i]);
						continue;
					}
				}
			}
			if($unit=="box"){
				//Note: all 3 must match
				if($fixture==$split[0]){
					if($shelf==$split[1]){
						if($box==$split[2]){
							unset($infoarray[$barcode][$i]);
							continue;
						}
					}
				}
			}	
		}
		
		//If there aren't any left:
		if(empty($infoarray[$barcode]))
			//delete that barcode
			unset($infoarray[$barcode]);
	}

	$_SESSION["barcode"] = $infoarray;

	//redirect back to scan.php
	Header("Location: scan.php");
?>
