<?php

namespace App\Test;

require_once dirname(dirname(__FILE__)).'/foods/food/index.php';

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
     $foodItem = new FoodItem();    
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
	unset($foodItem);
    }

	public function testChangeItemStock(){
		$foodItem->editItemStock('2');
		$this->assertEquals($foodItem->getItemStock(),'2');
	}

}
?>
