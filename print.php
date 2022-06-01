<?php
require 'vendor/autoload.php';
require 'classes/phpheader.php';
require '../Libraries/fpdf16/fpdf.php';
require "classes/GetCheckDigit.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


if(!isset($_POST['download']) && !isset($_POST['email'])){
	//echo "<h2> Must choose to download or email! </h2>";
	//die();
	handleError("Must choose to download or email!");
}

$items = getItems($_POST);

$filename='';	

//if user selects pdf
if($_POST['type'] == '1'){
	$pdf=new FPDF();
	$pdf->AddFont('Code93', '', 'Code-93.php');
	$pdf->AliasNbPages();

	if(!isset($_POST['pagination'])){
		addPdfItems($pdf,$items,
			$_POST['dept'],
			$_POST['storenum'],
			$_POST['fixnum'],
			$_POST['shelfnum'],
			$_POST['boxnum']);
	}
	else{
		foreach($items as $fixture=>$fixtureItems){
			if($_POST['paginationOptions']=== 'fixture'){
				addPdfItems($pdf,$fixtureItems,
					$_POST['dept'],
					$_POST['storenum'],
					$fixture);
				continue;
			}

			foreach($fixtureItems as $shelf=>$shelfItems){
				if($_POST['paginationOptions']=== 'shelf'){
					addPdfItems($pdf,$shelfItems,
						$_POST['dept'],
						$_POST['storenum'],
						$fixture,
						$shelf);
					continue;
				}
				foreach($shelfItems as $box=>$boxItems){
					if($_POST['paginationOptions'] === 'box'){
						addPdfItems($pdf,$boxItems,
							$_POST['dept'],
							$_POST['storenum'],
							$fixture,
							$shelf,
							$box);
						continue;
					}
				}
			}
		}
	}

	//allow user to print the document
	//$path = "C:/Apache24/htdocs/info/inventory/pdf.pdf";
	$path = "pdf.pdf";
	$filename=$path;
	$pdf->Output($path, 'F');
}

//if user selects XLSX 
elseif($_POST['type'] == '2'){
	$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

	//bc this mf really likes scientific notation
	$spreadsheet->getDefaultStyle()->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

	$sheet = $spreadsheet->getActiveSheet();

	$index = 1;

	if(!isset($_POST['pagination'])){
		addXlsxItems($sheet,$items,$index, //$index is passed by reference - see function definition.
			$_POST['dept'],
			$_POST['storenum'],
			$_POST['fixnum'],
			$_POST['shelfnum'],
			$_POST['boxnum']);
	}
	else{
		foreach($items as $fixture=>$fixtureItems){
			if($_POST['paginationOptions'] === 'fixture'){
				addXlsxItems($sheet,$fixtureItems,$index,
					$_POST['dept'],
					$_POST['storenum'],
					$fixture);
				$sheet->setBreak("A$index",\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
				$index++;
				continue;
			}

			foreach($fixtureItems as $shelf=>$shelfItems){
				if($_POST['paginationOptions']=== 'shelf'){
					addXlsxItems($sheet,$shelfItems,$index,
						$_POST['dept'],
						$_POST['storenum'],
						$fixture,
						$shelf);
					$sheet->setBreak("A$index",\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
					$index++;
					continue;
				}
				foreach($shelfItems as $box=>$boxItems){
					if($_POST['paginationOptions'] === 'box'){
						addXlsxItems($sheet,$boxItems,$index,
							$_POST['dept'],
							$_POST['storenum'],
							$fixture,
							$shelf,
							$box);
						$sheet->setBreak("A$index",\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
						$index++;
						continue;
					}
				}
			}
		}


	}
	foreach(range('A','E') as $column){
		$sheet->getColumnDimension($column)->setAutoSize(true);
	}

	$sheetFilename = 'reports/'.$_POST['dept'].'-'.$_POST['storenum'].'-'.$_POST['fixnum'].'-'.$_POST['shelfnum'].'-'.$_POST['boxnum'].'.xlsx';

	$filename = $sheetFilename;

	$writer = new Xlsx($spreadsheet);
	$writer->save($sheetFilename);

	$shortFilename = substr($sheetFilename, strpos($sheetFilename, "/")+1);
}

if(isset($_POST['email'])){
	sendEmail($_POST['emailDest'],$filename);
}
if(isset($_POST['download'])){
	if($_POST['type']=='1'){ //download pdf
		header("Location: $filename");
	}

	if($_POST['type']=='2'){ 
		//download spreadsheet to user's computer
		if(!file_exists($filename)){
			handleError("Error: File does not exist!",null,true);
			//die("<h1>Error: File does not exist</h1>");
		}else{
			header("Location: $sheetFilename");
			//header("Cache-Control: public");
			//header("Content-Description: File Transfer");
			//header("Content-Disposition: attachment; filename=$shortFilename");
			//header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
			//header("Content-Transfer-Encoding: binary");
			//readfile($sheetFilename);
		}
	}
}

function addXlsxItems($sheet,$items,&$index,$dept='',$store='',$fixt='',$shelf='',$box=''){

	//fills in spreadsheet header
	$sheet->setCellValue("A$index", 'Department: '.$dept);
	$sheet->setCellValue("B$index", 'Store: '.$store);
	$sheet->setCellValue("C$index", 'Fixture #: '.$fixt);
	$sheet->setCellValue("D$index", 'Shelf #: '.$shelf);
	$sheet->setCellValue("E$index", 'Box #: '.$box);
	$index++;	
	$sheet->setCellValue("A$index", 'SKU');
	$sheet->setCellValue("B$index", 'DESCRIPTION');
	$sheet->setCellValue("C$index", 'PRICE');
	$sheet->setCellValue("D$index", 'QTY');
	$index++;

	for($i=0; $i < count($items); $i++){
		$sku = 	$items[$i]['sku'];

		$itemDetails = getItemDetails($sku,$items[$i]['store']);   

		$desc = $itemDetails['description'];
		$price = $itemDetails['price'];
		$qty = $items[$i]['QTY'];

		$sheet->setCellValueExplicit("A$index",$sku,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$sheet->setCellValue('B'.$index, $desc);
		$sheet->setCellValue("C$index",$price)->getStyle("C$index")->getNumberFormat()->setFormatCode('0.00');
		// $sheet->getStyle("C$index")->getNumberFormat()->setFormatCode('0.00');
		$sheet->setCellValue('D'.$index, $qty);

		$index++;
	}

	$index++;
}

function getItems($data){
	global $conn;
	$items=array();
	//Get the og items list
	//requires store and/or dept, but you need at least one.
	if(!isset($data['storenum'])){
		if(!isset($data['dept'])){	
			handleError("must choose a store and/or department!");
		}
		//dept is set, store is not.
		$query = "SELECT * FROM SCANNED WHERE dept = '".$data['dept']."'";
	}
	else{
		$query = "SELECT * FROM SCANNED WHERE store = '".$data['storenum']."'";

		if($data['dept'])
			$query .= " AND dept = '".$data['dept']."'";
	}

	if($data['fixnum']){
		$query .= " AND fixtureNum = '".$data['fixnum']."'";
	}

	if($data['shelfnum']){
		$query .= " AND shelfNum = '".$data['shelfnum']."'";
	}

	if($data['boxnum']){
		$query .= " AND boxNum = '".$data['boxnum']."'";
	}

	$query .= " GROUP BY sku"; 


	//if there are any results:
	$result = $conn->query($query);
	if($result->num_rows > 0)
		$items = mysqli_fetch_all($result,MYSQLI_ASSOC); 
	else{
		handleError("No results.","<a href='printing.php'>Print something else</a><br>");
	}

	if(!isset($data['pagination'])){
		//Just return all items in the specified combo
		return $items;
	}

	//Separate out into fixtures
	$returnArray=array();
	$fixtures = array_unique(array_column($items,"fixtureNum")); 
	foreach($fixtures as $fixture){
		$returnArray[(string)$fixture]=array();
		foreach($items as $item){
			if($item['fixtureNum']===$fixture){
				array_push($returnArray[(string)$fixture],$item);
			}
		}
	}

	if($data['paginationOptions']==='fixture'){
		return $returnArray;
	}

	//Separate out into shelves
	foreach($returnArray as $fixture=>$items){
		$shelves=array_unique(array_column($items,"shelfNum")); 
		// echo "<pre>";var_dump($shelves); echo "</pre>";
		$returnArray[(string)$fixture]=array();
		foreach($shelves as $shelf){
			$returnArray[(string)$fixture][(string)$shelf]=array();
			foreach($items as $item){ 
				// echo "<pre>";var_dump($item); echo "</pre>";
				if($item['shelfNum']===$shelf)
					array_push($returnArray[(string)$fixture][(string)$shelf],$item);
			}
		}
	}

	if($data['paginationOptions']==='shelf')
		return $returnArray;

	//Separate out into boxes
	foreach($returnArray as $fixture=>$shelves){
		foreach($shelves as $shelf=>$items){
			$boxes=array_unique(array_column($items,"boxNum"));
			$returnArray[(string)$fixture][(string)$shelf]=array();
			foreach($boxes as $box){
				$returnArray[(string)$fixture][(string)$shelf][(string)$box]=array();
				foreach($items as $item){
					if($item['boxNum']===$box)
						array_push($returnArray[(string)$fixture][(string)$shelf][(string)$box],$item);
				}
			}
		}
	}

	// echo "<pre>";var_dump($returnArray); echo "</pre>";die();
	return $returnArray; 
}

function addPdfItems($pdf,$items,$dept='',$store='',$fixt='',$shelf='',$box=''){
	//calculate total price
	$totalPrice = 0.0;
	foreach($items as $item){
		$itemDetails = getItemDetails($item['sku'],$item['store']);
		$totalPrice += floatval(str_replace(",","",$itemDetails['price'])) * $item['QTY'];
		//commas break intval for some reason. remove them first!
	}
	$totalPrice = number_format($totalPrice,2);

	$generator = new Picqer\Barcode\BarcodeGeneratorPNG();

	$iteminfoposition = array(
		'x' => 17,
		'y' => 40
	);
	$barcodeposition = array(
		'x' => 31,
		'y' => 55,
		'textx' => 40,
		'texty' => 62
	);
	$rectposition = array(
		'x' => 10,
		'y' => 36,
		'width' => 95,
		'height' => 40
	);
	$spacer = 0;
	$pageNo=0;
	$totalPages = ceil(count($items)/12);

	for($i = 0; $i < count($items); $i++){ 
		$supplementalBook=false;
		$sku = 	$items[$i]['sku'];

		$itemDetails = getItemDetails($sku,$items[$i]['store']);
		$desc = $itemDetails['description'];
		$price = $itemDetails['price'];

		if($i % 12 == 0){
			$pageNo++;
			//repeat this every 12 items
			$pdf->AddPage();
			$pageNum++;
			//$pdf->SetMargins(19, 26, 19);
			$pdf->SetFont('Times','',12);

			//Don't you love working with PDFs?
			$separator="          ";
			$separatorSize = $pdf->GetStringWidth($separator);

			//First part of the header
			$headerPrefix = "Department: ".$dept.$separator."Store: ".$store;
			$pdf->SetXY(12,18);
			//$pdf->Text(25,18, $headerText);
			$pdf->Write(0.1,$headerPrefix);
			$prefixSize = $pdf->GetStringWidth($headerPrefix);

			//The fixture - separate, because they want it bolded
			//yes, there are a lot of magic numbers. if you change them you'll be cursed, i don't get it either
			$pdf->SetFont('Times','B',16); 
			$fixtureText = "Fixture #: ".$fixt;
			$fixtureSize = $pdf->GetStringWidth($fixtureText);
			$pdf->SetLineWidth(0.5); //like. what units are these? can't tell. just 0.5. one half. don't even worry about it dude
			$pdf->Rect($pdf->GetX()+$separatorSize+4,14,$fixtureSize+3,8);
			$pdf->Write(0.1,$separator.$fixtureText.$separator);
			$pdf->SetFont('Times','',12);
			$pdf->SetLineWidth(0.2);

			//The rest of it	
			$pdf->Text($pdf->GetX(),$pdf->GetY()+1.5,"Shelf #: ".$shelf."       Box #:  ".$box."   of     _______");
			$pdf->Text(13,28, "CS Counter(s):_______________________    RGIS Counter Initials:________     Total dollar value: $$totalPrice");
			//fills border
			$pdf->Rect(10,10, 191, 26);

			$pdf->Text(185, 285, "Page ".$pdf->PageNo()." of ".'{nb}');//nb thing: placeholder for total page count.
			//FPDF fills it in as document closes
			$pdf->Text(173, 290, "In unit: Page $pageNo of $totalPages");

			//adds instructions at bottom of page
			$pdf->Text(40, 280, "Scan all items in the left column, and then scan all items in the right column.");

			//resets positions for new page
			$iteminfoposition['x'] = 13;
			$iteminfoposition['y'] = 40;
			$barcodeposition['x'] = 16;
			$barcodeposition['y'] = 50;
			$barcodeposition['textx'] = 40;
			$barcodeposition['texty'] = 65;
			$rectposition['x'] = 10;
			$rectposition['y'] = 36;

			$rectposition['width'] = 95;

			$spacer = 0;
		}

		if($i % 12 == 6){
			//updates x positions & resets y positions
			//I read somewhere it's important to update y-coordinates first

			$iteminfoposition['y'] = 40;
			$barcodeposition['y'] = 50;
			$barcodeposition['texty'] = 65;
			$rectposition['y'] = 36;

			$iteminfoposition['x'] = 108;
			$barcodeposition['x'] = 111;
			$barcodeposition['textx'] = 135;
			$rectposition['x'] = 105;

			$rectposition['width'] = 96;

			$spacer = 96;
		}

		$pdf->SetFont('Times','',9);

		$pdf->SetXY($iteminfoposition['x'],$iteminfoposition['y']);
		$pdf->Write(0.1,"Description: ".$desc."   Price: ".$price."   ");

		$quantity = $items[$i]['QTY'];

		$quantityText = "QTY: ".$quantity;
		$quantityLength = $pdf->GetStringWidth($quantityText);
		$pdf->SetFont('Times','B',12);
		$pdf->Write(0.1,$quantityText);
		$pdf->SetFont('Times','',9);

		//barcode time.
		if(strlen($sku) == 18){ //textbook handling
			$supplementalBook=true;
			$supCode = substr($sku,13,5);
			$supPath = "images/$supCode.png";
			$sku = substr($sku,0,13);
		}

		//Get barcode type, depending on length:
		$type = $generator::TYPE_CODE_39; //fallback to this by default
		if(strlen($sku) == 12){
			$type = $generator::TYPE_UPC_A;
		}
		if(strlen($sku) == 8){
			$type = $generator::TYPE_EAN_8;
			//$type = $generator::TYPE_UPC_E;
			//$type = $generator::TYPE_CODE_39; //UPC_E wasn't scanning in correctly
		}
		if(strlen($sku) == 13){
			$type = $generator::TYPE_EAN_13;
		}

		$skupath = "images/".$sku.$type.".png";

		if(!file_exists($skupath)){
			try{
				//error_log($type);
				file_put_contents($skupath, $generator->getBarcode($sku, $type));
			}
			catch (Picqer\Barcode\Exceptions\InvalidCheckDigitException $e){
				$originalLength=strlen($sku);
				$sku = substr($sku,0,strlen($sku)-1);
				$checkDigit = (new GetCheckDigit)->getDigit($originalLength,$sku);
				$sku=$sku.$checkDigit;
			}
			catch (Exception $e){
				$type = $generator::TYPE_CODE_39;
				error_log("Inventory: bad barcode when printing: $sku, error code ".$e->getMessage());
				$sku = "*".$sku;
			}
			finally{
				$codedSku=$sku;
				if(substr($sku,0,1)==="*"){
					$codedSku=substr($sku,1,strlen($sku));
					$sku="INVALID ".$sku;
				}
				file_put_contents($skupath, $generator->getBarcode($codedSku, $type));
			}
		}	

		if($supplementalBook){
			if(!file_exists($supPath)){
				$type = $generator::TYPE_EAN_5;
				file_put_contents($supPath, $generator->getBarcode($supCode, $type));
			}
		}
		//get width of barcode
		//list($imagewidth, $h) = getimagesize($filepath);

		if($supplementalBook){
			$pdf->Image($skupath, $barcodeposition['x'],$barcodeposition['y'],56,10);
			$pdf->Image($supPath, $barcodeposition['x']+57,$barcodeposition['y']+1,26,10);
		}	
		else if(strlen($sku)>13){
			$pdf->Image($skupath, $barcodeposition['x'],$barcodeposition['y'],85,10);
		}
		else{
			$pdf->Image($skupath, $barcodeposition['x']+15,$barcodeposition['y'],40,10);
		}

		$pdf->SetFont('Times','',9);
		$charWidth=0; //i don't know lol
		if($supplementalBook){
			$pdf->Text($barcodeposition['textx']-12-(strlen($sku)/2)*$charWidth,$barcodeposition['texty'], $sku);
			$pdf->Text($barcodeposition['textx']+44-(strlen($supCode)/2)*$charWidth,$barcodeposition['texty'], $supCode);
		}
		else{
			$pdf->Text($barcodeposition['textx']+2-(strlen($sku)/2)*$charWidth, $barcodeposition['texty'], $sku);
		}

		//draw border
		$pdf->Rect($rectposition['x'], $rectposition['y'], $rectposition['width'], $rectposition['height']);

		//updates y positions
		$iteminfoposition['y'] += 40;
		$barcodeposition['y'] += 40;
		$barcodeposition['texty'] += 40;
		$rectposition['y'] += 40;
	}
}

function sendEmail($id,$attachment){
	require("../Libraries/PHPMailer_v5.1/class.phpmailer.php");
	global $conn;
	$mail = new PHPMailer(true);

	$webmaster_email = "no-reply@bookstore.utah.edu"; //Reply to this email ID
	$mail->From = $webmaster_email;
	$mail->FromName = "Inventory Application";
	$mail->AddReplyTo($webmaster_email,"Webmaster");
	$mail->WordWrap = 50; // set word wrap
	$mail->IsHTML(true); // send as HTML
	$mail->Subject = "Inventory Report";

	$mail->AddAttachment($attachment);

	$query = "SELECT * FROM users WHERE username='".$_SESSION['username']."'";
	$me = mysqli_fetch_assoc(mysqli_query($conn,$query));

	$mail->Body = $me['name']." has submitted this inventory report for your review.";

	if($id==-1){
		$email = "mwahls@campusstore.utah.edu";
		$name = "Michael Wahls";
	}
	else{
		$query = "SELECT * FROM users WHERE id=$id";
		$manager = mysqli_fetch_assoc(mysqli_query($conn,$query));
		$email = $manager['username']."@umail.utah.edu";
		$name = $manager['name'];
	}

	$mail->AddAddress($email, $name);

	//$mail->AddAddress("emoss@campusstore.utah.edu", "Ella Moss");

	//Send the email, report an error if the send fails
	if(!$mail->Send()) {
		$errorText = "Mailer Error: " . $mail->ErrorInfo . "\n Contact IT";
		handleError("Mailer Error");
		//echo "<p>Mailer Error: " . $mail->ErrorInfo;
		//echo "Contact IT.</p>";
	}
	else {
		if(!isset($_POST['download'])){
			$head=['title'=>"Success"];
			$header = "Success";
			include 'classes/header.php';
			echo "<div class='contain'>The report has been sent successfully.</div>";
			include 'classes/footer.php';
		}
	}

}


