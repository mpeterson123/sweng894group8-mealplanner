<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\GroceryListItemFactory;
use Base\Models\GroceryListItem;
use Base\Models\FoodItem;
use Base\Repositories\FoodItemRepository;


class GroceryListItemFactoryTest extends TestCase {
    // Variables to be reused
    private $groceryListItemFactory,
        $foodItemRepositoryStub;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        /////////////////////////////
        // Stub foodItemRepositoryStub //
        /////////////////////////////
        $this->foodItemRepositoryStub = $this
            ->createMock(FoodItemRepository::class);

        // Configure the stub.
        $foodItemStub = $this->createMock(FoodItem::class);
        $this->foodItemRepositoryStub->method('find')
            ->will($this->returnValue($foodItemStub));


        /////////////////////
        // Create instance //
        /////////////////////
        $this->groceryListItemFactory = new GroceryListItemFactory(
            $this->foodItemRepositoryStub
        );
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->groceryListItemFactory);
    }

    public function testCreateGroceryListItemFactory(){
        $groceryListItemFactory = new GroceryListItemFactory($this->foodItemRepositoryStub);

    	$this->assertInstanceOf(
            'Base\Factories\GroceryListItemFactory',
            $groceryListItemFactory,
            'Object must be instance of GroceryListItemFactory');
    }

    public function testMakeGroceryListItemWithId(){
        $groceryListItemArray = array(
            'id' => 1234,
            'foodItemId' => 1,
            'amount' => 27.00,
        );

        $groceryListItem = $this->groceryListItemFactory->make($groceryListItemArray);
    	$this->assertInstanceOf(
            'Base\Models\GroceryListItem',
            $groceryListItem,
            'Object must be instance of GroceryListItem');

        $this->assertEquals($groceryListItem->getId(), $groceryListItemArray['id']);
        $this->assertInstanceOf(
            'Base\Models\FoodItem',
            $groceryListItem->getFoodItem(),
            'Object must be instance of FoodItem');
        $this->assertEquals($groceryListItem->getAmount(), $groceryListItemArray['amount']);
    }

    public function testMakeGroceryListItemWithoutId(){
        $groceryListItemArray = array(
            'foodItemId' => 1,
            'amount' => 27.00,
        );

        $groceryListItem = $this->groceryListItemFactory->make($groceryListItemArray);
    	$this->assertInstanceOf(
            'Base\Models\GroceryListItem',
            $groceryListItem,
            'Object must be instance of GroceryListItem');

        $this->assertEquals($groceryListItem->getId(), NULL);
        $this->assertInstanceOf(
            'Base\Models\FoodItem',
            $groceryListItem->getFoodItem(),
            'Object must be instance of FoodItem');
        $this->assertEquals($groceryListItem->getAmount(), $groceryListItemArray['amount']);
    }
}
