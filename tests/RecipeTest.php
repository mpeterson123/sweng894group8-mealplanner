<?php

namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/Recipe.php';
require_once dirname(dirname(__FILE__)).'/app/models/Ingredient.php';
require_once dirname(dirname(__FILE__)).'/app/models/FoodItem.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use Base\Models\Recipe;
use Base\Models\Ingredient;
use Base\Models\FoodItem;

class RecipeTest extends TestCase {
    // Variables to be reused
    private $recipe;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->recipe = new Recipe('Sugar Cookies','Sugar Cookies',6);
      $ingredient = new Ingredient(new FoodItem('flour','',''),2);
      $this->recipe->addIngredient($ingredient);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->recipe);
    }
    public function testAddIngredient(){
      $this->assertEquals($this->recipe->getIngredientByName('flour')->getQuantity(),2);
    }

  	public function testEditIngredient(){
  		$this->recipe->getIngredientByName('flour')->setQuantity('5');
  		$this->assertEquals($this->recipe->getIngredientQuantity('flour'), '5');
  	}

}
?>
>>>>>>> e5f8b178da8c943428c7fffce9fca3aeb71e9e0a
