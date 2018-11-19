<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Models\FoodItem;
use Base\Models\Unit;
use Base\Models\Category;


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
    	$this->assertInstanceOf(
            'Base\Models\FoodItem',
            new FoodItem(),
            'Object must be instance of FoodItem');
    }

	////////////////////////////////////////////////////////////////////////////
    // Id  //
	////////////////////////////////////////////////////////////////////////////
    public function testSetAndGetId(){
        $id = 1;
        $this->foodItem->setId($id);
        $this->assertEquals($this->foodItem->getId(), $id);
    }

    public function testIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->foodItem->setId(NULL);
    }

    public function testIdIsAnInteger(){
        $intId = 123;
        $this->foodItem->setId($intId);
        $this->assertInternalType('integer', $this->foodItem->getId());
    }

    public function testIdCannotBeNegative(){
        $negativeId = -1;
        $this->expectException(\Exception::class);
        $this->foodItem->setId($negativeId);
    }

    public function testIdCannotBeZero(){
        $zeroId = 0;
        $this->expectException(\Exception::class);
        $this->foodItem->setId($zeroId);
    }

	////////////////////////////////////////////////////////////////////////////
    // Name //
	////////////////////////////////////////////////////////////////////////////

    public function testSetName(){
        $name = 'Apple';
        $this->foodItem->setName($name);
        $this->assertEquals($this->foodItem->getName(), $name);
    }

    public function testNameCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->foodItem->setName('');
    }

    public function testNameCannotBeLongerThan50Chars(){
        $longName = '123456789012345678901234567890123456789012345678901';
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

    public function testNameIsString(){
        $stringName = 'Apple';
        $this->foodItem->setName($stringName);
        $this->assertInternalType('string', $stringName);
    }

    public function testNonStringNamesAreRejected(){
        $nonStringName = 0;
        $this->expectException(\Exception::class);
        $this->foodItem->setName($nonStringName);
    }

	////////////////////////////////////////////////////////////////////////////
    // Stock //
	////////////////////////////////////////////////////////////////////////////

    public function testGetStock(){
        $stock = 3;
        $this->foodItem->setStock($stock);
        $this->assertEquals($this->foodItem->getStock(), $stock, 'Stock must be ${stock}.');
    }

    public function testStockCannotBeNegative(){
        $negativeStock = -1;
        $this->expectException(\Exception::class);
        $this->foodItem->setStock($negativeStock);
    }

    public function testStockIsStoredAsFloat(){
        $floatStock = '123.45';
        $this->foodItem->setStock($floatStock);
        $this->assertInternalType('float', $this->foodItem->getStock());
    }

	////////////////////////////////////////////////////////////////////////////
    // Unit //
	////////////////////////////////////////////////////////////////////////////

    public function testGetUnit(){
        $unit = $this->createMock(Unit::class);
        $this->foodItem->setUnit($unit);
        $this->assertEquals($this->foodItem->getUnit(), $unit);
    }

    public function testUnitIsOfTypeUnit(){
        $unit = $this->createMock(Unit::class);
        $this->foodItem->setUnit($unit);
        $this->assertEquals($this->foodItem->getUnit(), $unit);

        $this->assertInstanceOf(
            'Base\Models\Unit',
            $unit,
            'Object must be instance of Unit');
    }

	////////////////////////////////////////////////////////////////////////////
    // Category //
	////////////////////////////////////////////////////////////////////////////
    public function testGetCategory(){
        $category = $this->createMock(Category::class);
        $this->foodItem->setCategory($category);
        $this->assertEquals($this->foodItem->getCategory(), $category);
    }

    public function testCategoryIsOfTypeCategory(){
        $category = $this->createMock(Category::class);
        $this->foodItem->setCategory($category);
        $this->assertEquals($this->foodItem->getCategory(), $category);

        $this->assertInstanceOf(
            'Base\Models\Category',
            $category,
            'Object must be instance of Category');
    }

	////////////////////////////////////////////////////////////////////////////
    // UnitsInContainer //
	////////////////////////////////////////////////////////////////////////////
    public function testGetUnitsInContainer(){
        $unitsInContainer = 34.28;
        $this->foodItem->setUnitsInContainer($unitsInContainer);
        $this->assertEquals($this->foodItem->getUnitsInContainer(), $unitsInContainer);
    }

    public function testUnitsInContainerCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->foodItem->setUnitsInContainer(NULL);
    }

    /**
     * @dataProvider tooLowUnitsInContainerProvider
     */
    public function testUnitsInContainerCannotBeZeroOrNegative($containerCost){
        $this->expectException(\Exception::class);
        $this->foodItem->setUnitsInContainer($containerCost);
    }

    public function tooLowUnitsInContainerProvider()
    {
        return [
            'zero' => [0],
            'negative one' => [-1],
            'long negative number' => [-999999999],
            'very small number' => [-0.0000000000000000000000000000000000000001]
        ];
    }

    public function testUnitsInContainerIsStoredAsFloat(){
        $floatUnitsInContainer = '123.45';
        $this->foodItem->setUnitsInContainer($floatUnitsInContainer);
        $this->assertInternalType('float', $this->foodItem->getUnitsInContainer());
    }

    /**
     * @dataProvider tooHighUnitsInContainerProvider
     */
    public function testUnitsInContainerCannotBeOver100000($containerCost){
        $this->expectException(\Exception::class);
        $this->foodItem->setUnitsInContainer($containerCost);
    }

    public function tooHighUnitsInContainerProvider()
    {
        return [
            'Too high by one thousandth' => [100000.01],
            '100001' => [100001],
            'Too high integer' => [10000000000000000000000000000],
            'Too high decimal' => [100000000.5325]
        ];
    }

    /**
     * @dataProvider inRangeUnitsInContainerProvider
     */
    public function testUnitsInContainerIsBetween0AndBelowOrEqualTo999Point99($containerCost){
        $this->foodItem->setUnitsInContainer($containerCost);
        $this->assertEquals($this->foodItem->getUnitsInContainer(), $containerCost);
    }

    public function inRangeUnitsInContainerProvider()
    {
        return [
            '999 dot 99 units' => [999.99],
            '0 0001' => [0.0001],
            '5555' => [555]
        ];
    }

    public function testNonNumericUnitsInContainerIsRejected(){
        $unitsInContainer = "50.";
        $this->expectException(\Exception::class);
        $this->foodItem->setUnitsInContainer($unitsInContainer);
    }

	////////////////////////////////////////////////////////////////////////////
    // Container Cost //
	////////////////////////////////////////////////////////////////////////////
    public function testGetContainerCost(){
        $containerCost = 5.00;
        $this->foodItem->setContainerCost($containerCost);
        $this->assertEquals($this->foodItem->getContainerCost(), $containerCost);
    }

    public function testContainerCostCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->foodItem->setContainerCost(NULL);
    }

    /**
     * @dataProvider tooLowContainerCostProvider
     */
    public function testContainerCostCannotBeZeroOrNegative($containerCost){
        $this->expectException(\Exception::class);
        $this->foodItem->setContainerCost($containerCost);
    }

    public function tooLowContainerCostProvider()
    {
        return [
            'zero' => [0],
            'negative one' => [-1],
            'long negative number' => [-999999999],
            'very small number' => [-0.0000000000000000000000000000000000000001]
        ];
    }

    /**
     * @dataProvider tooHighContainerCostProvider
     */
    public function testContainerCostCannotBeOver9999Point99($containerCost){
        $this->expectException(\Exception::class);
        $this->foodItem->setContainerCost($containerCost);
    }

    public function tooHighContainerCostProvider()
    {
        return [
            'Too high by one thousandth' => [9999.991],
            '10000' => [10000],
            'Too high integer' => [10000000000000000000000000000],
            'Too high decimal' => [100000.5325]
        ];
    }

    /**
     * @dataProvider inRangeContainerCostProvider
     */
    public function testContainerCostIsBetween0AndBelowOrEqualTo9999Point99($containerCost){
        $this->foodItem->setContainerCost($containerCost);
        $this->assertEquals($this->foodItem->getContainerCost(), $containerCost);
    }

    public function inRangeContainerCostProvider()
    {
        return [
            '9999 and 99 cents' => [9999.99],
            '0 0001' => [0.0001],
            '5555' => [5555]
        ];
    }

    public function testContainerCostIsStoredAsFloat(){
        $floatContainerCost = '123.45';
        $this->foodItem->setContainerCost($floatContainerCost);
        $this->assertInternalType('float', $this->foodItem->getContainerCost());
    }

    public function testNonNumericContainerCostIsRejected(){
        $containerCost = "74.";
        $this->expectException(\Exception::class);
        $this->foodItem->setContainerCost($containerCost);
    }

	////////////////////////////////////////////////////////////////////////////
    // Unit Cost //
	////////////////////////////////////////////////////////////////////////////

    public function testGetUnitCost(){
        $unitsInContainer = 100.00;
        $containerCost = 2.00;
        $expectedUnitCost = 0.02;

        $this->foodItem->setUnitsInContainer($unitsInContainer);
        $this->foodItem->setContainerCost($containerCost);

        $this->foodItem->setUnitCost();
        $this->assertEquals($this->foodItem->getUnitCost(), $expectedUnitCost);
    }

    public function testUnitCostRequiresContainerCost(){
        $unitsInContainer = 100.00;
        $this->foodItem->setUnitsInContainer($unitsInContainer);

        $this->expectException(\Exception::class);
        $this->foodItem->getUnitCost();
    }

    public function testUnitCostUnitsInContainer(){
        $containerCost = 2.00;
        $this->foodItem->setContainerCost($containerCost);

        $this->expectException(\Exception::class);
        $this->foodItem->getUnitCost();
    }

}
