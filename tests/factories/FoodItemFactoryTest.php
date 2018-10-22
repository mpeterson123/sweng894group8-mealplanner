<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\FoodItemFactory;
use Base\Models\FoodItem;
use Base\Models\Category;
use Base\Models\Unit;
use Base\Core\DatabaseHandler;
use Base\Repositories\CategoryRepository;
use Base\Repositories\UnitRepository;


class FoodItemTest extends TestCase {
    // Variables to be reused
    private $foodItemFactory,
        $categoryRepositoryStub,
        $unitRepositoryStub;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        /////////////////////////////
        // Stub categoryRepositoryStub //
        /////////////////////////////
        $this->categoryRepositoryStub = $this
            ->createMock(CategoryRepository::class);

        // Configure the stub.
        $categoryStub = $this->createMock(Category::class);
        $this->categoryRepositoryStub->method('find')
            ->will($this->returnValue($categoryStub));

        /////////////////////////
        // Stub unitRepositoryStub //
        /////////////////////////
        $this->unitRepositoryStub = $this->createMock(UnitRepository::class);

        // Configure the stub.
        $unitStub = $this->createMock(Unit::class); // TODO Change to stub
        $this->unitRepositoryStub->method('find')
            ->will($this->returnValue($unitStub));


        /////////////////////
        // Create instance //
        /////////////////////
        $this->foodItemFactory = new FoodItemFactory(
            $this->categoryRepositoryStub,
            $this->unitRepositoryStub
        );
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->foodItemFactory);
    }

    public function testCreateFoodItemFactory(){
        $foodItemFactory = new FoodItemFactory($this->categoryRepositoryStub, $this->unitRepositoryStub);

    	$this->assertInstanceOf(
            'Base\Factories\FoodItemFactory',
            $foodItemFactory,
            'Object must be instance of FoodItemFactory');
    }

    public function testMakeFoodItemWithId(){
        $foodItemArray = array(
            'id' => 1234,
            'name' => 'Bread',
            'category_id' => 1,
            'stock' => 1.00,
            'unit_id' => 1,
            'units_in_container' => 1.00,
            'container_cost' => 1.00,
            'user_id' => 1
        );

        $foodItem = $this->foodItemFactory->make($foodItemArray);
    	$this->assertInstanceOf(
            'Base\Models\FoodItem',
            $foodItem,
            'Object must be instance of FoodItem');

        $this->assertEquals($foodItem->getId(), $foodItemArray['id']);
        $this->assertEquals($foodItem->getName(), $foodItemArray['name']);
        $this->assertInstanceOf(
            'Base\Models\Category',
            $foodItem->getCategory(),
            'Object must be instance of Category');
        $this->assertEquals($foodItem->getStock(), $foodItemArray['stock']);
        $this->assertInstanceOf(
            'Base\Models\Unit',
            $foodItem->getUnit(),
            'Object must be instance of Unit');
        $this->assertEquals($foodItem->getUnitsInContainer(), $foodItemArray['units_in_container']);
        $this->assertEquals($foodItem->getContainerCost(), $foodItemArray['container_cost']);
        $this->assertEquals($foodItem->getUnitCost(), 1.00);
    }

    public function testMakeFoodItemWithoutId(){
        $foodItemArray = array(
            'name' => 'Bread',
            'category_id' => 1,
            'stock' => 1.00,
            'unit_id' => 1,
            'units_in_container' => 1.00,
            'container_cost' => 1.00,
            'user_id' => 1
        );

        $foodItem = $this->foodItemFactory->make($foodItemArray);
    	$this->assertInstanceOf(
            'Base\Models\FoodItem',
            $foodItem,
            'Object must be instance of FoodItem');

        $this->assertEquals($foodItem->getId(), NULL);
        $this->assertEquals($foodItem->getName(), $foodItemArray['name']);
        $this->assertInstanceOf(
            'Base\Models\Category',
            $foodItem->getCategory(),
            'Object must be instance of Category');
        $this->assertEquals($foodItem->getStock(), $foodItemArray['stock']);
        $this->assertInstanceOf(
            'Base\Models\Unit',
            $foodItem->getUnit(),
            'Object must be instance of Unit');
        $this->assertEquals($foodItem->getUnitsInContainer(), $foodItemArray['units_in_container']);
        $this->assertEquals($foodItem->getContainerCost(), $foodItemArray['container_cost']);
        $this->assertEquals($foodItem->getUnitCost(), 1.00);
    }
}
