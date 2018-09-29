<?php

namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class GroceryItem {
	private $fooditem,
    $quantity;

    public function __construct($f, $q) {
			if($f == ''){
	    	throw new \Exception("Food Item cannot be empty", 1);
	    }
		  if($q <= 0){
		  	throw new \Exception("Food Item quantity less than or equal to 0", 1);
	    }

			$this->fooditem = Trim($f);
			$this->quantity = $q;
    }

    public function getQuantity($f) {
			return $this->quantity;
    }

		public function setQuantity($q) {
			if($q <= 0){
	    	throw new \Exception("Food Item quantity less than or equal to 0", 1);
	    }

			$this->quantity = $q;
    }

	public function purchase($n,$q) {
		$this->quantity = $this->quantity - $q;
  }

}

?>
