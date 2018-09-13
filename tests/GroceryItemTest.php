<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/GroceryItem.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\GroceryItem;

class GroceryItemTest extends TestCase {
    // Variables to be reused
    private $groceryitem;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

        $food = 'Butter';
        $quantity = 1.3;
        $this->groceryitem = new GroceryItem($f, $q);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->groceryitem);
    }

    // Get the quantity of butter
    public function testGetQuantity($f){
      return $this->groceryItem->getQuantity('Butter');
    }

    // Set the quantity of butter
    public function testSetQuantity(){
      $this->groceryItem->setQuantity(2.2);
    }

    // Purchase 1.0 units of butter
    public function testPurchase(){
      $this->groceryItem->purchase('Butter',1.0);
    }
}
