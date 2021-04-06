<?php

	class Release {
		
		private $id;
		private $employee;
		private $status;
		private $creationDate;
		private $realizationDate;
		private $productsRelease;
		
		public function __construct($id, $employee, $status, $creationDate, /*$realizationDate,*/ 
			$productsRelease) {
			$this->id = $id;
			$this->employee = $employee;
			$this->status = $status;
			$this->productsRelease = $productsRelease;
			$this->creationDate = $creationDate;
			//$this->realizationDate = $realizationDate;
		}
		
		public function toAssocArray() {
			$array = array();
			$array['id'] = $this->id;
			$array['employee'] = $this->employee;
			$array['status'] = $this->status;
			$array['creationDate'] = $this->creationDate;
			$array['realizationDate'] = $this->realizationDate;
			$array['productsRelease'] = $this->productsRelease;
			return $array;
		}
	/*	
		public function __construct(object $release) {
			$this->id = null;
			$this->employee = $release->employeeId;
			$this->setStatus($release->status);
			$this->productsRelease = $release->productsRelease;
			$this->creationDate = self::getCurrentDateTime();
			$this->realizationDate = null;
		}
		*/
		public static function getCurrentDateTime() {
			return date_create();
		}
		public static function getDateTimeFormat(DateTime $dt){
			return $dt->format('Y-m-d H:i:s');
		}
		
		public function setStatus(Status $status) {
			if(Status::isValidValue($status))
				$this->status = $status;
			else
				$this->status = Status::PENDING;
		}
		
		public function getId() {
			return $this->id;
		}
		
		public function getEmployeeId() {
			return $this->employee;
		}
		
		public function getStatus() {
			return $this->status;
		}
		
		public function getCreationDate() {
			return $this->creationDate;
		} 
		
		public function getRealizationDate() {
			return $this->realizationDate;
		}
		
		public function getProducts() {
			return $this->productsRelease;
		}
	}
	
	abstract class Status extends BasicEnum {
		// const __default = self::OCZEKUJĄCY;
		
		const AWAITED = 1; // awaited
		const W_TRAKCIE = 2; // be pending
		const ZROBIONY = 3; // realized
	}
	
	abstract class ProductStatus extends BasicEnum {
		// const __default = self::OCZEKUJĄCY;
		
		const AWAITED = 1; // awaited
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