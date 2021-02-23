<?php

	class Release {
		
		private $id;
		private $employeeId;
		private $status;
		private $cDate;
		private $rDate;
		private $products;
		private $Surname;
		private $symbol;
		
		 public function __construct(object $release) {
			$this->id = null;
			$this->employeeId = $release->employeeId;
			$this->setStatus($release->status);
			$this->products = $release->productsRelease;
			$this->cDate = $this->getCurrentDateTime();
			$this->Surname = $release->Surname;
			$this->symbol = $release->symbol;
			//$this->rDate = null;
		}
		
		public function getCurrentDateTime() {
			return date_create();
		}
		public function getDateTimeFormat(DateTime $dt){
			return $dt->format('Y-m-d H:i:s');
		}
		
		public function setStatus(Status $status) {
			if(Status::isValidValue($status))
				$this->status = $status;
			else
				$this->status = Status::OCZEKUJĄCY;
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getEmployeeId() {
			return $this->employeeId;
		}
		
		public function getStatus() {
			return $this->status;
		}
		
		public function getCreationDate() {
			return $this->cDate;
		}
		
		public function getRealizationDate() {
			return $this->rDate;
		}
		
		public function getProducts() {
			return $this->products;
		}

		public function getEmployeeSurname() {
			return $this->Surname;
		}
	}
	
	abstract class Status extends BasicEnum {
		// const __default = self::OCZEKUJĄCY;
		
		const OCZEKUJĄCY = 1; // awaited
		const IN_PROGRESS = 2; // W_TRAKCIE be pending
		const ZROBIONY = 3; // realized
	}
	
	abstract class ProductStatus extends BasicEnum {
		// const __default = self::OCZEKUJĄCY;
		
		const OCZEKUJĄCY = 1; // awaited
		const BRAK_W_MAGAZYNIE = 2; 
		const WYDANY = 3; // realized
	}
	
	abstract class BasicEnum {
		
		private static $constCacheArray = NULL;

		private function __construct(){
		  /*
			Preventing instance :)
		  */
		 }

		private static function getConstantsNames() {
			$constants = self::getConstants();
			return array_keys($constants);
		}
		 
		private static function getConstants() {
			if (self::$constCacheArray == NULL) {
				self::$constCacheArray = [];
			}
			$calledClass = get_called_class();
			if (!array_key_exists($calledClass, self::$constCacheArray)) {
				$reflect = new ReflectionClass($calledClass);
				self::$constCacheArray[$calledClass] = $reflect->getConstants();
			}
			return self::$constCacheArray[$calledClass];
		}

		public static function isValidName($name, $strict = false) {
			$constants = self::getConstants();

			if ($strict) {
				return array_key_exists($name, $constants);
			}

			$keys = array_map('strtolower', array_keys($constants));
			return in_array(strtolower($name), $keys);
		}

		public static function isValidValue($value) {
			$values = array_values(self::getConstants());
			return in_array($value, $values, $strict = true);
		}
	}


?>