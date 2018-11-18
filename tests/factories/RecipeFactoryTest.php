<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\RecipeFactory;
use Base\Models\Recipe;
use Base\Models\Ingredient;
use Base\Repositories\IngredientRepository;
use Base\Core\DatabaseHandler;


class RecipeFactoryTest extends TestCase {
    // Variables to be reused
    private $recipeFactory;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

    	////////////////////////////////////////////////////////////////////////
        // Stub ingredientRepositoryStub //
    	////////////////////////////////////////////////////////////////////////
        $this->ingredientRepositoryStub = $this
            ->createMock(IngredientRepository::class);

        // Configure the stub.
        $ingredientStub = $this->createMock(Ingredient::class);
        $ingredientsArray = array(
            $ingredientStub,
            $ingredientStub,
            $ingredientStub
        );
        $this->ingredientRepositoryStub->method('allForRecipe')
            ->will($this->returnValue($ingredientsArray));

    	////////////////////////////////////////////////////////////////////////
        // Create instance //
    	////////////////////////////////////////////////////////////////////////
        $this->recipeFactory = new RecipeFactory(
            $this->ingredientRepositoryStub
        );
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->recipeFactory);
    }

    public function testCreateRecipeFactory(){
        $recipeFactory = new RecipeFactory($this->ingredientRepositoryStub);

    	$this->assertInstanceOf(
            'Base\Factories\RecipeFactory',
            $recipeFactory,
            'Object must be instance of RecipeFactory');
    }

    public function testMakeRecipeWithId(){
        $recipeArray = array(
            'id' => 1234,
            'name' => 'Stir Fry',
            'directions' => 'These are some directions for a recipe.',
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
        $this->assertEquals($recipe->getDirections(), $recipeArray['directions']);
        $this->assertEquals($recipe->getServings(), $recipeArray['servings']);
        $this->assertEquals($recipe->getSource(), $recipeArray['source']);
        $this->assertEquals($recipe->getNotes(), $recipeArray['notes']);

        // Get ingredients
        $this->assertInternalType('array',$recipe->getIngredients());
        $this->assertEquals(3,count($recipe->getIngredients()));
        foreach ($recipe->getIngredients() as $ingredient) {
            $this->assertInstanceOf('Base\Models\Ingredient', $ingredient);
        }
    }

    public function testMakeRecipeWithoutId(){
        $recipeArray = array(
            'name' => 'Stir Fry',
            'directions' => 'These are some directions for a recipe.',
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
        $this->assertEquals($recipe->getDirections(), $recipeArray['directions']);
        $this->assertEquals($recipe->getServings(), $recipeArray['servings']);
        $this->assertEquals($recipe->getSource(), $recipeArray['source']);
        $this->assertEquals($recipe->getNotes(), $recipeArray['notes']);

        // Get ingredients
        $this->assertInternalType('array',$recipe->getIngredients());
        $this->assertEquals(0,count($recipe->getIngredients()));
    }
}
