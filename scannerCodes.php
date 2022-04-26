<?php
include 'vendor/autoload.php';
//May want to make barcodes clickable - come up in a modal, so it's big and more scannable

 
$head=['title'=>'Fix Scanner',
	'styles'=>'<style>
table{
	border-collapse:collapse;
	margin-top:10px;
}
table td{
	border: 1px solid;
	padding:1%;
}
	</style>'
];
include 'classes/header.php';
?>
<div class="contain">
	<h2>Still under development</h2>
	Reprogram your scanner by scanning these codes, in order, following instructions.<br>
	If no scan line appears, hold the trigger for 5 seconds.<br>
	Contact IT if you continue to have problems.<br>
	<table>
		<tbody>
			<tr>
				<td>Hold trigger for 5 seconds, then scan code. Continue after you hear two double beeps.</td>
				<td><img alt="Barcode to perform a factory reset on the scanner" src="images/scanner/1_reset.jpg"></td>
			</tr>
			<tr>
			<!-- skip this one? can't remember -->
				<td>Scan code. Continue after you hear one double beep, followed by two more double beeps.</td>
				<td><img alt="Barcode to select USB-OEM" src="images/scanner/2_usb-oem.jpg"></td>
			</tr>
			<tr>
				<td>If no scan line appears, hold the trigger for 5 seconds (until it beeps). Scan code to enter programming mode.</td>
				<td><img alt="Barcode to enter programming mode" src="images/scanner/3_programming.jpg"></td>
			</tr>
			<tr>
				<td>Scan code to set volume to "medium."</td>
				<td><img alt="Barcode to set good read beep volume as medium" src="images/scanner/4_medium-volume.jpg"></td>
			</tr>
			<tr>
				<td>Scan code to add 5 digit supplementary code. Continue after you hear a beep.</td>
				<td><img alt="Barcode to add 5 digit supplementary code" src="images/scanner/5_enableP5.jpg"></td>
			</tr>
			<tr>
				<td>Scan code to exit programming mode.</td>
				<td><img alt="Barcode to exit programming mode" src="images/scanner/3_programming.jpg"></td>
			</tr>
		</tbody>
	</table>
	<br>
	You may need to unplug your scanner and plug it back in for changes to take effect.
</div>
<?php include 'classes/footer.php'; ?>
