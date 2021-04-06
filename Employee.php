<?php

class Employee {
		
	private $id;
	private $symbol;
	private $name;
	private $surname;
	
	public function __construct($id, $symbol, $name, $surname) {
		$this->id = $id;
		$this->symbol = $symbol;
		$this->name = $name;
		$this->surname = $surname;
	}
	
	public function toAssocArray() {
		$array = array();
		$array['id'] = $this->id;
		$array['symbol'] = $this->symbol;
		$array['name'] = $this->name;
		$array['surname'] = $this->surname;
		return $array;
	}
}

?>