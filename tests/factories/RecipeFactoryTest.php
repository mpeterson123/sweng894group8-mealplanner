<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\RecipeFactory;
use Base\Models\Recipe;
use Base\Models\Household;
use Base\Core\DatabaseHandler;
use Base\Repositories\HouseholdRepository;


class RecipeFactoryTest extends TestCase {
    // Variables to be reused
    private $recipeFactory;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

        /////////////////////
        // Create instance //
        /////////////////////
        $this->recipeFactory = new RecipeFactory();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->recipeFactory);
    }

    public function testCreateRecipeFactory(){
        $recipeFactory = new RecipeFactory();

    	$this->assertInstanceOf(
            'Base\Factories\RecipeFactory',
            $recipeFactory,
            'Object must be instance of RecipeFactory');
    }

    public function testMakeRecipeWithId(){
        $recipeArray = array(
            'id' => 1234,
            'name' => 'Stir Fry',
            'description' => 'This is a recipe description',
            'servings' => 4,
            'source' => 'http://example.com',
            'notes' => 'These are some recipe notes'
        );

        $recipe = $this->recipeFactory->make($recipeArray);
    	$this->assertInstanceOf(
            'Base\Models\Recipe',
            $recipe,
            'Object must be instance of Recipe');

        // Check primitive values
        $this->assertEquals($recipe->getId(), $recipeArray['id']);
        $this->assertEquals($recipe->getName(), $recipeArray['name']);
        $this->assertEquals($recipe->getDescription(), $recipeArray['description']);
        $this->assertEquals($recipe->getServings(), $recipeArray['servings']);
        $this->assertEquals($recipe->getSource(), $recipeArray['source']);
        $this->assertEquals($recipe->getNotes(), $recipeArray['notes']);
    }

    public function testMakeRecipeWithoutId(){
        $recipeArray = array(
            'name' => 'Stir Fry',
            'description' => 'This is a recipe description',
            'servings' => 4,
            'source' => 'http://example.com',
            'notes' => 'These are some recipe notes'
        );

        $recipe = $this->recipeFactory->make($recipeArray);
    	$this->assertInstanceOf(
            'Base\Models\Recipe',
            $recipe,
            'Object must be instance of Recipe');

        // Check primitive values
        $this->assertEquals($recipe->getId(), NULL);
        $this->assertEquals($recipe->getName(), $recipeArray['name']);
        $this->assertEquals($recipe->getDescription(), $recipeArray['description']);
        $this->assertEquals($recipe->getServings(), $recipeArray['servings']);
        $this->assertEquals($recipe->getSource(), $recipeArray['source']);
        $this->assertEquals($recipe->getNotes(), $recipeArray['notes']);
    }
}
