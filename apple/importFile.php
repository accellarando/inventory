<?php
	require '../vendor/autoload.php';
	require '../classes/phpheader.php';
	
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\Reader\IReader;
	$allowedFiletypes = array('xlsx', 'csv');
	
	if(isset($_FILES['report'])){
		$name = $_FILES['report']['name'];
		$filetype = substr($name, strrpos($name, ".")+1);
		$tmpname = $_FILES['report']['tmp_name'];
		
		if(!in_array($filetype, $allowedFiletypes)){
			echo "<h3>Error! Please upload one of the following filetypes:</h3><ul>";
			foreach($allowedFiletypes as $type){
				echo "<li>".$type."</li>";
			}
			echo "</ul>";
			die();
		}
	}
	
	//load the spreadsheet into a reader
	$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($tmpname);
	$sheet = $spreadsheet->getActiveSheet();
	$maxRowNum = $sheet->getHighestRow();
	$maxColNum = $sheet->getHighestColumn();
	
	//drop old data
	$query = "DELETE FROM apple WHERE 1;";
	$conn->query($query);
	
	$row = 2;
	
	//loop through sheet querying data into database
	while($row < $maxRowNum+1){
		$col = 'A';
		$query = "INSERT INTO apple (store, class, sku, vendorStyleNum, description, QTY, vendorNum) VALUES ('";
		
		while($col <= $maxColNum){
			$val = $sheet->getCell($col.$row)->getValue();
			$query .= $val;
			
			$col++;
			
			if($col <= $maxColNum){
				$query .="','";
			}
		}
		$query .= "');";
		$conn->query($query);
		
		$row++;
	}
$apple = true;
$head = ['title'=>'Data has been imported!'];
$header = "<strong>Data</strong> has been imported!";
include '../classes/header.php';
?>
<div class="contain" style="text-align: center;">
	<p>The data has been imported!</p><br><br>
</div>
<?php include '../classes/footer.php'; ?>
