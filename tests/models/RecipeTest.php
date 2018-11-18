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

	////////////////////////////////////////////////////////////////////////////
    // Instatiation //
	////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////
    // Id //
	////////////////////////////////////////////////////////////////////////////

    public function testSetAndGetId(){
        $id = 1;
        $this->recipe->setId($id);
        $this->assertEquals($this->recipe->getId(), $id);
    }

    public function testIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->recipe->setId(NULL);
    }

    public function testIdIsAnInteger(){
        $intId = 123;
        $this->recipe->setId($intId);
        $this->assertInternalType('integer', $this->recipe->getId());
    }

    public function testIdCannotBeNegative(){
        $negativeId = -1;
        $this->expectException(\Exception::class);
        $this->recipe->setId($negativeId);
    }

    public function testIdCannotBeZero(){
        $zeroId = 0;
        $this->expectException(\Exception::class);
        $this->recipe->setId($zeroId);
    }


	////////////////////////////////////////////////////////////////////////////
    // Directions //
	////////////////////////////////////////////////////////////////////////////

	////////////////////////////////////////////////////////////////////////////
    // Directions //
	////////////////////////////////////////////////////////////////////////////

    public function testSetDirections(){
        $variable = 'My Directions';
        $this->recipe->setDirections($variable);
        $this->assertEquals($this->recipe->getDirections(), $variable);
    }

    public function testDirectionsCannotBeLongerThan65535Chars(){
        $longDirections ='';
        for($i = 0; $i<65636; $i++){
            $longDirections.='a';
        }

        $this->expectException(\Exception::class);
        $this->recipe->setDirections($longDirections);
    }

    public function testDirectionsCannotHaveExtraWhitespace(){
        $variableWithWhitespace = ' My Directions   ';
        $expectedDirections =  'My Directions';
        $this->recipe->setDirections($variableWithWhitespace);

        $this->assertEquals($this->recipe->getDirections(), $expectedDirections,
            'Directions must be trimmed.');
    }

    public function testDirectionsIsString(){
        $stringDirections = 'Directions';
        $this->recipe->setDirections($stringDirections);
        $this->assertInternalType('string', $stringDirections);
    }

    public function testNonStringDirectionsAreRejected(){
        $nonStringDirections = 0;
        $this->expectException(\Exception::class);
        $this->recipe->setDirections($nonStringDirections);
    }

	////////////////////////////////////////////////////////////////////////////
    // Name //
	////////////////////////////////////////////////////////////////////////////

    public function testGetName(){
        $name = 'Meatloaf';
        $this->recipe->setName($name);
        $this->assertEquals($name, $this->recipe->getName());
    }

	////////////////////////////////////////////////////////////////////////////
    // Servings //
	////////////////////////////////////////////////////////////////////////////

    public function testGetServings(){
      $servings = 4;
      $this->recipe->setServings($servings);
      $this->assertEquals($servings, $this->recipe->getServings());
    }

	////////////////////////////////////////////////////////////////////////////
    // Source //
	////////////////////////////////////////////////////////////////////////////

    public function testGetSource(){
        $source = 'Allrecipes.com';
        $this->recipe->setSource($source);
        $this->assertEquals($source, $this->recipe->getSource());
    }

	////////////////////////////////////////////////////////////////////////////
    // Notes //
	////////////////////////////////////////////////////////////////////////////

    public function testGetNotes(){
      $notes = 'This is a note.';
      $this->recipe->setNotes($notes);
      $this->assertEquals($notes, $this->recipe->getNotes());
    }

	////////////////////////////////////////////////////////////////////////////
    // Ingredients //
	////////////////////////////////////////////////////////////////////////////

    public function testGetIngredients(){
        $this->recipe->addIngredient($this->ingredient);
        $this->assertInternalType('array', $this->recipe->getIngredients());
        $this->assertEquals($this->recipe->getIngredients()[0], $this->ingredient);
        $this->assertInstanceOf(
            'Base\Models\Ingredient',
            $this->recipe->getIngredients()[0],
            'Object must be instance of Ingredient');
    }


	////////////////////////////////////////////////////////////////////////////
    // AddIngredient //
	////////////////////////////////////////////////////////////////////////////

    public function testAddIngredient(){
      $this->recipe->addIngredient($this->ingredient);
      $this->assertSame($this->ingredient, $this->recipe->getIngredientByName('flour'));
    }

    public function testEditIngredient(){
  	    $this->recipe->getIngredientByName('flour')->setQuantity('5');
  	    $this->assertEquals($this->recipe->getIngredientByName('flour')->getQuantity(), 5);
    }
}
