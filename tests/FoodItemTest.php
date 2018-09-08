<?php
namespace App\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use App\FoodItem;


class FoodItemTest extends TestCase {
    // Variables to be reused
    private $foodItem;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->foodItem = new FoodItem();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->foodItem);
    }

    public function testCreateFoodItem(){
    	$this->assertInstanceOf(App\FoodItem, new FoodItem(), 'Object must be instance of FoodItem');
    }

    public function testChangeItemStock(){
      $foodItem->setStock('2');
      $this->assertEquals($foodItem->getStock(),'2');
    }

}
