<?php

namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\Ingredient;
use Base\Models\FoodItem;
use Base\Models\Quantity;
use Base\Models\Unit;
use Base\Models\Recipe;


class IngredientTest extends TestCase {
    // Variables to be reused
    private $ingredient,
      $food,
      $quantity;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */


    public function setUp(){
        $this->foodItem = $this->createMock(FoodItem::class);
        $this->quantity = $this->createMock(Quantity::class);
        $this->recipe = $this->createMock(Recipe::class);
        $this->unit = $this->createMock(Unit::class);

        $this->ingredient = new Ingredient($this->foodItem, $this->quantity, 1, $this->unit);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->ingredient);
    }

	////////////////////////////////////////////////////////////////////////////
	// Instatiation //
	////////////////////////////////////////////////////////////////////////////

    public function testCreateIngredient(){
      $this->assertInstanceOf(
          Ingredient::class,
          new Ingredient($this->foodItem, $this->quantity, 123, $this->unit),
          'Object must be instance of Ingredient.');
    }

	////////////////////////////////////////////////////////////////////////////
    // Food //
	////////////////////////////////////////////////////////////////////////////

    public function testGetFood(){
        $foodItem = $this->createMock(FoodItem::class);
        $this->ingredient->setFood($foodItem);
        $this->assertEquals($this->ingredient->getFood(), $foodItem);
    }

    public function testFoodIsOfTypeFood(){
        $foodItem = $this->createMock(FoodItem::class);
        $this->ingredient->setFood($foodItem);
        $this->assertEquals($this->ingredient->getFood(), $foodItem);

        $this->assertInstanceOf(
            'Base\Models\FoodItem',
            $foodItem,
            'Object must be instance of Food');
    }

	////////////////////////////////////////////////////////////////////////////
    // Quantity //
	////////////////////////////////////////////////////////////////////////////

    public function testGetQuantity(){
        $quantity = $this->createMock(Quantity::class);
        $this->ingredient->setQuantity($quantity);
        $this->assertEquals($this->ingredient->getQuantity(), $quantity);
    }

    public function testQuantityIsOfTypeQuantity(){
        $quantity = $this->createMock(Quantity::class);
        $this->ingredient->setQuantity($quantity);
        $this->assertEquals($this->ingredient->getQuantity(), $quantity);

        $this->assertInstanceOf(
            'Base\Models\Quantity',
            $quantity,
            'Object must be instance of Quantity');
    }

	////////////////////////////////////////////////////////////////////////////
    // RecipeId //
	////////////////////////////////////////////////////////////////////////////

    public function testSetAndGetRecipeId(){
        $recipeId = 1;
        $this->ingredient->setRecipeId($recipeId);
        $this->assertEquals($this->ingredient->getRecipeId(), $recipeId);
    }

    public function testRecipeIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->ingredient->setRecipeId(NULL);
    }

    public function testRecipeIdIsAnInteger(){
        $intRecipeId = 123;
        $this->ingredient->setRecipeId($intRecipeId);
        $this->assertInternalType('integer', $this->ingredient->getRecipeId());
    }

    public function testRecipeIdCannotBeNegative(){
        $negativeId = -1;
        $this->expectException(\Exception::class);
        $this->ingredient->setId($negativeId);
    }

    public function testRecipeIdCannotBeZero(){
        $zeroId = 0;
        $this->expectException(\Exception::class);
        $this->ingredient->setId($zeroId);
    }

	////////////////////////////////////////////////////////////////////////////
    // Id //
	////////////////////////////////////////////////////////////////////////////

    public function testSetAndGetId(){
        $id = 1;
        $this->ingredient->setId($id);
        $this->assertEquals($this->ingredient->getId(), $id);
    }

    public function testIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->ingredient->setId(NULL);
    }

    public function testIdIsAnInteger(){
        $intId = 123;
        $this->ingredient->setId($intId);
        $this->assertInternalType('integer', $this->ingredient->getId());
    }

    public function testIdCannotBeNegative(){
        $negativeId = -1;
        $this->expectException(\Exception::class);
        $this->ingredient->setId($negativeId);
    }

    public function testIdCannotBeZero(){
        $zeroId = 0;
        $this->expectException(\Exception::class);
        $this->ingredient->setId($zeroId);
    }

	////////////////////////////////////////////////////////////////////////////
    // Unit //
	////////////////////////////////////////////////////////////////////////////

    public function testGetUnit(){
        $unit = $this->createMock(Unit::class);
        $this->ingredient->setUnit($unit);
        $this->assertEquals($this->ingredient->getUnit(), $unit);
    }

    public function testUnitIsOfTypeUnit(){
        $unit = $this->createMock(Unit::class);
        $this->ingredient->setUnit($unit);
        $this->assertEquals($this->ingredient->getUnit(), $unit);

        $this->assertInstanceOf(
            'Base\Models\Unit',
            $unit,
            'Object must be instance of Unit');
    }
}

?>
