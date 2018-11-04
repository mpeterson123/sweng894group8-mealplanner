<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\MealFactory;
use Base\Models\Meal;
use Base\Models\Recipe;
use Base\Core\DatabaseHandler;
use Base\Repositories\RecipeRepository;


class MealFactoryTest extends TestCase {
    // Variables to be reused
    private $mealFactory,
        $recipeRepositoryStub,
        $unitRepositoryStub;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        /////////////////////////////
        // Stub recipeRepositoryStub //
        /////////////////////////////
        $this->recipeRepositoryStub = $this
            ->createMock(RecipeRepository::class);

        // Configure the stub.
        $recipeStub = $this->createMock(Recipe::class);
        $this->recipeRepositoryStub->method('find')
            ->will($this->returnValue($recipeStub));

        /////////////////////
        // Create instance //
        /////////////////////
        $this->mealFactory = new MealFactory(
            $this->recipeRepositoryStub
        );
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->mealFactory);
    }

    public function testCreateMealFactory(){
        $mealFactory = new MealFactory($this->recipeRepositoryStub);

    	$this->assertInstanceOf(
            'Base\Factories\MealFactory',
            $mealFactory,
            'Object must be instance of MealFactory');
    }

    public function testMakeMealWithId(){
        $mealArray = array(
            'id' => 1234,
            'recipeId' => 1,
            'scaleFactor' => 5.02,
            'date' => '2018-01-01 12:00:00',
        );

        $meal = $this->mealFactory->make($mealArray);
    	$this->assertInstanceOf(
            'Base\Models\Meal',
            $meal,
            'Object must be instance of Meal');
        $this->assertEquals($meal->getId(), $mealArray['id']);
        $this->assertInstanceOf(
            'Base\Models\Recipe',
            $meal->getRecipe(),
            'Current recipe must be instance of Recipe'
        );
        $this->assertEquals($meal->getScaleFactor(), $mealArray['scaleFactor']);
        $this->assertEquals($meal->getDate(), $mealArray['date']);
    }

    public function testMakeMealWithoutId(){
        $mealArray = array(
            'recipeId' => 1,
            'scaleFactor' => 5.02,
            'date' => '2018-01-01 12:00:00',
        );

        $meal = $this->mealFactory->make($mealArray);
    	$this->assertInstanceOf(
            'Base\Models\Meal',
            $meal,
            'Object must be instance of Meal');
        $this->assertEquals($meal->getId(), NULL);
        $this->assertInstanceOf(
            'Base\Models\Recipe',
            $meal->getRecipe(),
            'The recipe must be instance of Recipe'
        );
        $this->assertEquals($meal->getScaleFactor(), $mealArray['scaleFactor']);
        $this->assertEquals($meal->getDate(), $mealArray['date']);
    }
}