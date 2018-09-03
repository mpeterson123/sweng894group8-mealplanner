<?php

namespace App\Test;

require_once dirname(dirname(__FILE__)).'/recipes/index.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use App\Recipe;


class RecipeTest extends TestCase {
    // Variables to be reused
    private $recipe;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->recipe = new Recipe();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->recipe);
    }

    /**
     * Test scaling up a recipe
     */
    public function testscaleRecipeUp(){
      $ingredient = new Ingredient('sugar', 2);
      $scaleFactor = 2;
      $this->recipe->addIngredient($ingredient);
      $this->recipe->scale($scaleFactor);
      $this->assertEquals(
        $this->recipe->getIngredientByName('sugar')
        ->getQuantity(), 4, 'Ingredient must be scaled by factor of'.$scaleFactor);
    }

    /**
     * Test scaling down a recipe
     */
    public function testscaleRecipeDown(){
      $ingredient = new Ingredient('sugar', 2);
      $scaleFactor = 0.5;
      $this->recipe->addIngredient($ingredient);
      $this->recipe->scale($scaleFactor);
      $this->assertEquals(
        $this->recipe->getIngredientByName('sugar')
        ->getQuantity(), 1, 'Ingredient must be scaled by factor of'.$scaleFactor);
    }

    /**
     * Mark recipe completed
     */
    public function testMarkRecipeCompleted(){
      $this->recipe->complete();
      $this->assertTrue($this->recipe->isComplete(), 'Recipe must be completed.');
    }

  	public function testNewRecipe(){
  		$newId = $recipe->addRecipe(name, 'NewRecipe');
  		$this->assertEquals($recipe->getRecipe($newId)->getName(),'NewRecipe');
  	}

  	public function testEditIngredient(){
  		$editId = 1;
  		$recipe->getRecipe($editId)->getIngredient('flour')->editQuantity('5');
  		$this->assertEquals($recipe->getRecipe($editId)->getIngredientQuantity('flour'), '5');
  	}

}
?>
>>>>>>> e5f8b178da8c943428c7fffce9fca3aeb71e9e0a
