<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/GroceryList.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\GroceryList;


class GroceryListTest extends TestCase {
    // Variables to be reused
    private $grocerylist;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->grocerylist = new GroceryList();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->grocerylist);
    }


    public function populateList() {
		    // Read all grocery items and quantities from GroceryItem model
    }

    public function getEntireList() {
		    return this->groceryitemarray;
    }

    // Fill the grocery list with an array
    public function testPopulate(){
        $array = array("Butter","Egg","Flour");
        
        $this->grocerlist->populate($array);
    }

    // Return the entire grocery list
    public function testGetEntireList(){
        return $this->grocerlist->getEntireList();
    }

}
