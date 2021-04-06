<?php

class ProductRelease {
		
	private $product;
	private $status;
	private $requested_quantity;
	
	public function __construct($product, $status, $requested_quantity) {
		$this->product = $product;
		$this->status = $status;
		$this->requested_quantity = $requested_quantity;
	}
	
	public function toAssocArray() {
		$array = array();
		$array['product'] = $this->product;
		$array['status'] = $this->status;
		$array['requested_quantity'] = $this->requested_quantity;
		return $array;
	}
}

?>