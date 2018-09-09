<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/FoodItem.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\FoodItem;


class FoodItemTest extends TestCase {
    // Variables to be reused
    private $foodItem;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

        $name = 'Banana';
        $category = null;
        $unit = null;
        $stock = 3;
        $cost = null;
        $this->foodItem = new FoodItem($name, $category, $unit, $stock, $cost);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->foodItem);
    }

    public function testCreateFoodItem(){
        $name = 'Chicken';
        $category = null;
        $unit = null;
        $stock = null;
        $cost = null;

    	$this->assertInstanceOf(
            'Base\Models\FoodItem',
            new FoodItem($name, $category, $unit, $stock, $cost),
            'Object must be instance of FoodItem');
    }

    //////////
    // Name //
    //////////

    public function testGetName(){
        $this->assertEquals($this->foodItem->getName(), 'Banana');
    }

    public function testSetName(){
        $this->foodItem->setName('Apple');
        $this->assertEquals($this->foodItem->getName(), 'Apple');
    }

    public function testNameCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->foodItem->setName('');
    }

    public function testNameCannotBeLongerThan20Chars(){
        $longName = '123456789012345678901234567890';
        $this->expectException(\Exception::class);
        $this->foodItem->setName($longName);
    }

    public function testNameCannotHaveExtraWhitespace(){
        $nameWithWhitespace = '       Apple   ';
        $expectedName =  'Apple';
        $this->foodItem->setName($nameWithWhitespace);

        $this->assertEquals($this->foodItem->getName(), $expectedName,
            'Name must be trimmed.');
    }

    public function testNameMustBeUnique(){

    }

    ///////////
    // Stock //
    ///////////

    public function testGetItemStock(){
        $this->assertEquals($this->foodItem->getStock(), 3, 'Stock must be 3.');
    }

    public function testChangeItemStock(){
        $newStock = 2;
        $this->assertEquals($this->foodItem->getStock(), 3, 'Original stock must be 3');
        $this->foodItem->setStock($newStock);
        $this->assertEquals($this->foodItem->getStock(), $newStock, 'New stock must be 2');
    }

    public function testStockCannotBeNegative(){
        $negativeStock = -1;
        $this->expectException(\Exception::class);
        $this->foodItem->setStock($negativeStock);
    }







}
