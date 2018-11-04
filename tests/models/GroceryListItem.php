<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Models\GroceryListItem;
use Base\Models\Unit;
use Base\Models\FoodItem;


class GroceryListItemTest extends TestCase {
    // Variables to be reused
    private $groceryListItem;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        $this->groceryListItem = new GroceryListItem();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->groceryListItem);
    }

    public function testCreateGroceryListItem(){
    	$this->assertInstanceOf(
            'Base\Models\GroceryListItem',
            new GroceryListItem(),
            'Object must be instance of GroceryListItem');
    }

    /////////
    // Id  //
    /////////
    public function testSetAndGetId(){
        $id = 1;
        $this->groceryListItem->setId($id);
        $this->assertEquals($this->groceryListItem->getId(), $id);
    }

    public function testIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->groceryListItem->setId(NULL);
    }

    public function testIdIsAnInteger(){
        $intId = 123;
        $this->groceryListItem->setId($intId);
        $this->assertInternalType('integer', $this->groceryListItem->getId());
    }

    public function testNonIntIdIsRejected(){
        $nonIntId = '123';
        $this->expectException(\Exception::class);
        $this->groceryListItem->setId($nonIntId);
    }


    //////////////
    // FoodItem //
    //////////////
    public function testGetFoodItem(){
        $foodItem = $this->createMock(FoodItem::class);
        $this->groceryListItem->setFoodItem($foodItem);
        $this->assertEquals($this->groceryListItem->getFoodItem(), $foodItem);
    }

    public function testFoodItemIsOfTypeFoodItem(){
        $foodItem = $this->createMock(FoodItem::class);
        $this->groceryListItem->setFoodItem($foodItem);
        $this->assertEquals($this->groceryListItem->getFoodItem(), $foodItem);

        $this->assertInstanceOf(
            'Base\Models\FoodItem',
            $foodItem,
            'Object must be instance of FoodItem');
    }

    /////////////
    // Amount //
    ////////////
    public function testGetAmount(){
        $amount = 34.28;
        $this->groceryListItem->setAmount($amount);
        $this->assertEquals($this->groceryListItem->getAmount(), $amount);
    }

    public function testAmountCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->groceryListItem->setAmount(NULL);
    }

    /**
     * @dataProvider tooLowAmountProvider
     */
    public function testAmountCannotBeZeroOrNegative($amount){
        $this->expectException(\Exception::class);
        $this->groceryListItem->setAmount($amount);
    }

    public function tooLowAmountProvider()
    {
        return [
            'zero' => [0],
            'negative one' => [-1],
            'long negative number' => [-999999999],
            'very small number' => [-0.0000000000000000000000000000000000000001]
        ];
    }

    public function testAmountIsStoredAsFloat(){
        $floatAmount = '123.45';
        $this->groceryListItem->setAmount($floatAmount);
        $this->assertInternalType('float', $this->groceryListItem->getAmount());
    }

    /**
     * @dataProvider tooHighAmountProvider
     */
    public function testAmountCannotBeOver9999Point99($amount){
        $this->expectException(\Exception::class);
        $this->groceryListItem->setAmount($amount);
    }

    public function tooHighAmountProvider()
    {
        return [
            'Too high by one thousandth' => [999.991],
            '1000' => [1000],
            'Too high integer' => [10000000000000000000000000000],
            'Too high decimal' => [100000.5325]
        ];
    }

    /**
     * @dataProvider inRangeAmountProvider
     */
    public function testAmountIsBetween0AndBelowOrEqualTo999Point99($amount){
        $this->groceryListItem->setAmount($amount);
        $this->assertEquals($this->groceryListItem->getAmount(), $amount);
    }

    public function inRangeAmountProvider()
    {
        return [
            '999 dot 99 units' => [999.99],
            '0 0001' => [0.0001],
            '5555' => [555]
        ];
    }

    public function testNonNumericAmountIsRejected(){
        $amount = "50.";
        $this->expectException(\Exception::class);
        $this->groceryListItem->setAmount($amount);
    }

}
