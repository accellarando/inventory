<?php
include_once 'phpheader.php';
/*Define $head = ["title" => "page title",
 *					["scripts" => (optional script tags for header)]
 *					["styles" => (optional style tags for header)]]
 *		 $header = "Text for header of page"
 */
if(!isset($header))
	$header=$head['title'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">

		<title><?php echo $head['title'];?></title>

		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

		<link rel="stylesheet" type="text/css" media="all" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/dark-hive/jquery-ui.css">

		<link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,700,900" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="/inventory/style.css">
		<link rel="shortcut icon" href="/inventory/favicon.ico" />
		<?php if(isset($head['scripts'])) echo $head['scripts']; ?>
		<?php if(isset($head['styles'])) echo $head['styles'];?>
	</head>

	<body>
		<div id="title" class="contain">
			<span class="textcenter" id="titleText">
				<h1><?php echo $header; ?></h1>
			</span>
		</div>
		<div class="contain">
			<img src="/inventory/images/UCSLogos.png" alt="University Campus Store">
		</div>

		<!-- Main body of site -->

