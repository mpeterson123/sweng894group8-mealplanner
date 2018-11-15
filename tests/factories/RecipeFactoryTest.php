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
use Base\Repositories\HouseholdRepository;
use Base\Factories\CategoryFactory;
use Base\Repositories\CategoryRepository;
use Base\Factories\UnitFactory;
use Base\Repositories\UnitRepository;
use Base\Factories\FoodItemFactory;
use Base\Repositories\FoodItemRepository;
use Base\Factories\IngredientFactory;
use Base\Repositories\IngredientRepository;


class RecipeFactoryTest extends TestCase {
    // Variables to be reused
    private $recipeFactory,
    $host,
    $user,
    $pass,
    $dbName,
    $charset,
    $dbh,
    $db;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

        ///////////////////////////////////
        // Stub ingredientRepositoryStub //
        ///////////////////////////////////
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

        /////////////////////
        // Create instance //
        /////////////////////
        $this->host = getenv("HTTP_dbLocalHost");
        $this->dbName   = getenv("HTTP_dbName");
        $this->user = getenv("HTTP_dbLocalUser");
        $this->pass = getenv("HTTP_dbPass");
        $this->charset = 'utf8';

        $this->dbh = DatabaseHandler::getInstance();

        $this->db = new \mysqli($this->host, $this->user, $this->pass,$this->dbName);
        $this->db->autocommit(FALSE);

        $categoryFactory = new CategoryFactory($this->dbh->getDB());
        $categoryRepository = new CategoryRepository($this->dbh->getDB(), $categoryFactory);

        $unitFactory = new UnitFactory($this->dbh->getDB());
        $unitRepository = new UnitRepository($this->dbh->getDB(), $unitFactory);

        $foodItemFactory = new FoodItemFactory($categoryRepository, $unitRepository);
        $foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $foodItemFactory);

        $ingredientFactory = new IngredientFactory($foodItemRepository, $unitRepository);
        $ingredientRepository = new IngredientRepository($this->dbh->getDB(), $ingredientFactory);

        $this->recipeFactory = new RecipeFactory($ingredientRepository);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->recipeFactory);
      unset($this->host);
      unset($this->user);
      unset($this->pass);
      unset($this->dbName);
      unset($this->charset);

      $this->db->close();

      unset($this->dbh);
      unset($this->db);
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
    }
}
