<?php

namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class GroceryList {
    private $groceryitemarray;

    public function __construct() {
		    $this->groceryitemarray = array();
    }

    public function getEntireList()

      // Look up meals in given timeframe

      // Look up food inventory/stock

      // Subtract Food inventory from meal need times Scale

      // If negative, add absolute value to grocery item array

      return $this->groceryitemarray;
    }

}

?>
