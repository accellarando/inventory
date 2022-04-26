<?php
session_start();
include '../session-timeout.php';
if($_SESSION["verified"] != "v3r1f13d-adm1n"){
	header("Location: http://www.info.campusstore.utah.edu/inventory/admin/index.php");
}
	
$files = $_FILES["csvs"];
//var_dump($uploadedFiles);
for($i=0;$i<count($files['name']);$i++){
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
		$filepath = escapeshellarg($files['tmp_name'][$i]);
		$filename = $files['name'][$i];
		//removes all non-numeric characters
		$storeNum = escapeshellarg(preg_replace("/[^0-9]/","",$filename));

		if($fileType != "text/csv" || $storeNum==''){
			echo "<h1>Error: Invalid file(s)!</h1>";
			echo "<br>Please upload one CSV per store, with the store number in the filename.";
			die();
		}
		$fileArray[strval($storeNum)]=$filepath;
	}

	$fileArray = json_encode($fileArray);
	//var_dump($fileArray);


	//run python script on file
	//the python script actually pulls in the data
	
	//Bash
	//If this isn't going, you probably have a permissions problem.
	//$command = "/usr/local/bin/python3 csvImporter.py -j $fileArray > logs/$now.txt "; //add 2>&1 to include errors. Could be a security risk.

	//Windows
	$command = "start /B C:\Users\Administrator\AppData\Local\Programs\Python\Python38-32\python C:\Apache24\htdocs\inventory\csvImporter.py -j $fileArray > logs/$now.txt 2>&1";
	//echo $command;
	
	#exec($command);
	pclose(popen($command, "r"));
	sleep(5);
}

?>

<html>
<head>
	<meta charset="utf-8">
	
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	
	<title>File Upload</title>
	<!--<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    
	<link rel="stylesheet" type="text/css" media="all" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css">
	<!--jquery.timepicker.min.css-->
	
	<link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="../style.css">
     
</head>

<body>
	<div id="title" class="contain">
		<span class="textcenter" id="titleText">
			<h1>File Upload</h1>
		</span>
	</div>
	<div class="contain">
		<img src="../images/UCSLogos.png">
	</div>
	<div class="contain">
		<h1>The file upload is complete.</h1>
		Parser is running. Ella is still debugging this since some major changes
		<p><a href="<?php echo "logs/".$now.".txt"; ?>">Click here</a> to see output when it's ready.</p>
    </div>
<footer>
	<hr>
    	<h4 class="textcenter">&copy; University of Utah 2016<script>new Date().getFullYear()>2015&&document.write(" - "+new Date().getFullYear());</script></h4>
</footer>
</body>
</html>

