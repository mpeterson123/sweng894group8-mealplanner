<?php

namespace App\Test;

require_once dirname(dirname(__FILE__)).'/recipes/index.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use App\Recipes;


class RecipeTest extends TestCase {
    // Variables to be reused
    private $recipe;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
     $recipe = new Recipe();    
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
	unset($recipe);
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
