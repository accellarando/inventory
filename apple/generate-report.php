<?php
	require '../vendor/autoload.php';
	require '../classes/phpheader.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	
	$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	
	//fills in spreadsheet header
	$sheet->setCellValue('A1', 'CLASS');
	$sheet->setCellValue('B1', 'SKU');
	$sheet->setCellValue('C1', 'VENDOR STYLE #');
	$sheet->setCellValue('D1', 'DESCRIPTION');
	$sheet->setCellValue('E1', 'ON HAND');
	$sheet->setCellValue('F1', 'ON HAND COUNT');
	
	$query = "SELECT * FROM apple;";
	$result = $conn->query($query)->fetch_all();
	
	$index = 2;
	
	foreach($result as $row){
		$class = $row[2];
		$sku = $row[3];
		$vendorStyleNum = $row[4];
		$description = $row[5];
		$qty = $row[6];
		$vendorNum = $row[7];
		$counted = '';
		
		$query = "SELECT (QTY) from apple_scanned WHERE sku='".$sku."' AND DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '".$_POST['drb']."' AND '".$_POST['dre']."';";
		//echo $query;
		//die();
		$resultTwo = $conn->query($query)->fetch_all();
		
		if(empty($resultTwo)){
			$query = "SELECT (QTY) from apple_scanned WHERE vendorStyleNum='".$vendorStyleNum."' AND DATE_FORMAT(date, '%Y-%m-%d') BETWEEN '".$_POST['drb']."' AND '".$_POST['dre']."';";
			$resultTwo = $conn->query($query)->fetch_all();
		}
		
		
		$counted = 0;
		foreach($resultTwo as $array){
			$counted += $array[0];
		}
		
		//fill row
		$sheet->setCellValue('A'.$index, $class);
		$sheet->setCellValue('B'.$index, $sku);
		$sheet->setCellValue('C'.$index, $vendorStyleNum);
		$sheet->setCellValue('D'.$index, $description);
		$sheet->setCellValue('E'.$index, $qty);
		$sheet->setCellValue('F'.$index, $counted);
		
		$index++;
	}
	
	//update header, download spreadsheet to user's machine
	$sheetFilename = 'reports/'.$_POST['drb'].'-'.$_POST['dre'].'-report.xlsx';
	
	$writer = new Xlsx($spreadsheet);
	$writer->save($sheetFilename);
	
	$shortFilename = substr($sheetFilename, strpos($sheetFilename, "/")+1);

	//download spreadsheet to user's computer
	if(!file_exists($sheetFilename)){
		die("<h1>Error: File does not exist</h1>");
	}else{
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$shortFilename");
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Transfer-Encoding: binary");
		
		readfile($sheetFilename);
	}
?>
