<?php
$admin = true;
include '../classes/phpheader.php';

$files = $_FILES["csvs"];

for($i=0;$i<count($files['name']);$i++){
	if(substr($files['name'][$i],strlen($files['name'][$i])-3,3)!=='csv' && $files['type'][$i]!=='text/csv')
		handleError('Invalid filetype! Please upload a CSV with store number in the filename.');
	$path = "uploads/".$files['name'][$i];
	move_uploaded_file($files['tmp_name'][$i], $path);
	$files['tmp_name'][$i] = $path;
	//array_push($files,$path);
}

$now = date('Y-m-d_H.i.s');

//checkStoreIsRecorded($storeNum);
readFilesIntoDB($files);

function checkStoreIsRecorded($storeNum){ //ngl, i don't know what this is for. but i won't delete it
	$found = false;

	$f = fopen('stores.txt', 'r');
	while($line = fgets($f) && !$found){
		if(strpos($line,$storeNum) !== false){
			$found = true;
		}
	}
	fclose($f);

	if(!$found){
		$f = fopen('stores.txt', 'a');
		fwrite($f, "\n".$storeNum);
		fclose($f);
	}
}

function readFilesIntoDB($files){
	global $now;
	$numberOfFiles = count($files['name']);
	$fileArray = array();
	for($i=0;$i<$numberOfFiles;$i++){
		$fileType = $files['type'][$i];
		$filepath = $files['tmp_name'][$i];
		$filename = $files['name'][$i];
		//removes all non-numeric characters
		$storeNum = preg_replace("/[^0-9]/","",$filename);
		$fileArray[strval((int)$storeNum)]=$filepath;
	}

	$fileArray = base64_encode(json_encode($fileArray)); //cmd was eating my quotes. thanks again for breaking everything, windows

	//run python script on file
	//the python script actually pulls in the data
	
	//Bash
	//If this isn't going, you probably have a permissions problem.
	$logfile = "logs/$now.txt";
	$cleanLogfile = escapeshellarg($logfile);
	$command = (strpos(php_uname('s'),"Windows")===false) ? "/usr/local/bin/python3 csvImporter.py -j $fileArray -l $logfile > $logfile" : "start /B C:\Users\Administrator\AppData\Local\Programs\Python\Python38-32\python C:\Apache24\htdocs\inventory\csvImporter.py -j $fileArray -l $cleanLogfile > $logfile 2>&1";
	
	//echo $command;

	//Windows
	//$command = "start /B C:\Users\Administrator\AppData\Local\Programs\Python\Python38-32\python csvImporter.py -j $fileArray > logs/$now.txt";
	
	pclose(popen($command,"r"));
	sleep(1);
}

$head = ['title'=>'File Upload'];
$header = "File Upload";
include '../classes/header.php';

?>
<div class="contain">

	<h1>The file upload is complete.</h1>
	Parser is running. 
	<p><a href="<?php echo "logs/".$now.".txt"; ?>">Click here</a> to see output when it's ready.</p>
</div>
<?php include '../classes/footer.php'; ?>
