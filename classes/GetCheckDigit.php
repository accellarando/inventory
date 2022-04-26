<?php
class GetCheckDigit extends Picqer\Barcode\Types\TypeEanUpcBase{
	public function getDigit($length,$sku){
		$this->length=$length;
		$data = $this->calculateChecksumDigit($sku);
		return $data;
	}
}
?>
