<?php

namespace Base\Models;

class GroceryList {
  private $groceryitemarray

    public function __construct() {
		$this->groceryitemarray = array();
    }

	public function populateList() {
		// Read all grocery items and quantities from GroceryItem model
    }
	
    public function getEntireList() {
		return this->groceryitemarray;
    }
}

?>
