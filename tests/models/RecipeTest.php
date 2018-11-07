<?php

namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use Base\Models\Recipe;
use Base\Models\Ingredient;
use Base\Models\FoodItem;

class RecipeTest extends TestCase {
    // Variables to be reused
    private $recipe;
    private $foodItem;
    private $ingredient;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->recipe = new Recipe('Sugar Cookies', '1. Preheat oven to 350',6);
      $this->foodItem = new FoodItem();
      $this->foodItem->setName('flour');
      $this->ingredient = new Ingredient($this->foodItem, 2, 1, 1);
      $this->recipe->addIngredient($this->ingredient);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->recipe);
    }

    public function testAddIngredient(){
      $this->recipe->addIngredient($this->ingredient);
      $this->assertSame($this->ingredient, $this->recipe->getIngredientByName('flour'));
    }

     public function testEditIngredient(){
  	$this->recipe->getIngredientByName('flour')->setQuantity('5');
  	$this->assertEquals($this->recipe->getIngredientByName('flour')->getQuantity(), 5);
     }

    public function testSetDirections(){
      $dirs = 'Meatloaf Directions';
      $this->recipe->setDirections($dirs);
      $this->assertEquals($dirs, $this->recipe->getDirections());
    }

    public function testSetName(){
      $name = 'Meatloaf';
      $this->recipe->setName($name);
      $this->assertEquals($name, $this->recipe->getName());
    }

    public function testSetServings(){
      $servings = 4;
      $this->recipe->setServings($servings);
      $this->assertEquals($servings, $this->recipe->getServings());
    }

    public function testSetSource(){
      $source = 'Allrecipes.com';
      $this->recipe->setSource($source);
      $this->assertEquals($source, $this->recipe->getSource());
    }

    public function testSetNotes(){
      $notes = 'This is a note.';
      $this->recipe->setNotes($notes);
      $this->assertEquals($notes, $this->recipe->getNotes());
    }

    public function testGetIngredients() {
      //THere is currently one ingredient in the array
      $actual = $this->recipe->getIngredients();
      $this->assertEquals($this->ingredient, $actual[0], '');
    }
}
?>
>>>>>>> e5f8b178da8c943428c7fffce9fca3aeb71e9e0a
