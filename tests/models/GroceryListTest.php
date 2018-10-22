<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

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

    // Return the entire grocery list
    public function testGetEntireGroceryList(){
        return $this->grocerylist->getEntireList();
    }

    // Mark item on grocery list as purchased
    public function testMarkItemAsPurchased(){

    }

}
