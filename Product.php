<?php

class Product{
		
	private $id;
	private $quantity;
	private $productname;
	private $productsymbol;
	
	public function __construct($id, $quantity, $name, $symbol) {
		$this->id = $id;
		$this->quantity = $quantity;
		$this->productname = $name;
		$this->productsymbol = $symbol;
	}
	
	public function toAssocArray() {
		$array = array();
		$array['id'] = $this->id ;
		$array['quantity'] = $this->quantity;
		$array['productname'] = $this->productname;
		$array['productsymbol'] = $this->productsymbol;
		return $array;
	}
}


?>