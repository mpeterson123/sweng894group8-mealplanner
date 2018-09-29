<?php

namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use Base\Models\Meal;
use Base\Models\Recipe;
use Base\Models\Ingredient;
use Base\Models\FoodItem;

class MealTest extends TestCase {
    // Variables to be reused
    private $meal;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $recipe = new Recipe('Sugar Cookies','Sugar Cookies',6);
      $this->meal = new Meal($recipe,'2018-10-01',1);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->meal);
    }
    /**
     * Test scaling up a recipe
     */
    public function testscaleRecipeUp(){
      $ingredient = new Ingredient(new FoodItem('sugar','',''), 2);
      $scaleFactor = 2;
      $this->meal->getRecipe()->addIngredient($ingredient);
      $this->meal->scale($scaleFactor);
      $this->assertEquals(
        $this->meal->getIngredientQuantity('sugar'), 4, 'Ingredient must be scaled by factor of'.$scaleFactor);
    }

    /**
     * Test scaling down a recipe
     */
    public function testscaleRecipeDown(){
      $ingredient = new Ingredient(new FoodItem('sugar','',''), 2);
      $scaleFactor = 0.5;
      $this->meal->getRecipe()->addIngredient($ingredient);
      $this->meal->scale($scaleFactor);
      $this->assertEquals(
        $this->meal->getIngredientQuantity('sugar'), 1, 'Ingredient must be scaled by factor of'.$scaleFactor);
    }

    /**
     * Mark recipe completed
     */
    public function testMarkMealCompleted(){
      $this->meal->complete();
      $this->assertTrue($this->meal->isComplete(), 'Recipe must be completed.');
    }

}
?>
