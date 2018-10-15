<?php

namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\Ingredient;
use Base\Models\FoodItem;
use Base\Models\Quantity;
use Base\Models\Unit;
use Base\Models\Category;


class IngredientTest extends TestCase {
    // Variables to be reused
    private $ingredient,
      $food,
      $quantity;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */


    public function setUp(){
      $this->foodUnit = new Unit('lbs', 'lbs', 16);
      $this->category = new Category(1,'Baking');
      $this->food= new FoodItem(1, 'Flour', 1, $this->foodUnit, $this->category, 1, 5, 5);
      $this->ingrUnit = new Unit('Cup', 'C', 8);
      $this->quantity= new Quantity('1', $this->ingrUnit);
      $this->ingredient = new Ingredient($this->food, $this->quantity, '1', $this->ingrUnit);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->foodUnit);
      unset($this->category);
      unset($this->food);
      unset($this->ingrUnit);
      unset($this->quantity);
      unset($this->ingredient);
    }

    public function testCreateIngredient(){
      $this->assertInstanceOf(Ingredient::class, $this->ingredient, 'Object must be instance of Ingredient.');
    }

    public function testConstructFood() {
      $this->assertEquals($this->food, $this->ingredient->getFood(), 'Food wasn\'t set up properly in constructor.');
    }

    public function testConstructQuantity() {
      $this->assertEquals($this->quantity, $this->ingredient->getQuantity(), 'Quantity wasn\'t set up properly in constructor.');
    }

    public function testSetFood(){
      $fi = new FoodItem(1, 'Broccoli', 1, $this->foodUnit, $this->category, 1, 5, 5);
      $this->ingredient->setFood($fi);
    	$this->assertEquals($fi, $this->ingredient->getFood(), '');
    }

    public function testSetQuantity(){
      $q = new Quantity('2', $this->ingrUnit);
      $this->ingredient->setQuantity($q);
    	$this->assertEquals($q, $this->ingredient->getQuantity(), '');
    }

    public function testSetRecipeId() {
      $this->ingredient->setRecipeId('2');
      $this->assertEquals('2', $this->ingredient->getRecipeId(), '');
    }

    public function testSetId() {
      $this->ingredient->setId('1');
      $this->assertEquals('1', $this->ingredient->getId(), '');
    }

    public function testSetUnit() {
      $unit = new Unit('grams(s)', 'g', 1);
      $this->ingredient->setUnit($unit);
      $this->assertEquals($unit, $this->ingredient->getUnit(), '');
    }
}

?>
