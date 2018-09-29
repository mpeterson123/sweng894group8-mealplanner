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
    private $ingredient;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->recipe = new Recipe('Sugar Cookies', 'Sugar Cookies',6);
      $this->ingredient = new Ingredient(new FoodItem('flour','',''),2);
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
      $this->assertEquals($this->recipe->getIngredientByName('flour')->getQuantity(),2);
    }

  	public function testEditIngredient(){
  		$this->recipe->getIngredientByName('flour')->setQuantity('5');
  		$this->assertEquals($this->recipe->getIngredientQuantity('flour'), '5');
  	}

    public function testSetDescription(){
      $desc = 'Meatloaf Description';
      $this->recipe->setDescription($desc);
      $this->assertEquals($desc, $this->recipe->getDescription());
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

}
?>
>>>>>>> e5f8b178da8c943428c7fffce9fca3aeb71e9e0a
