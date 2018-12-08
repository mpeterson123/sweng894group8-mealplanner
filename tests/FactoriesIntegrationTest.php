<?php
namespace Base\Test;

require_once __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\User;
use Base\Models\Household;
use Base\Models\Unit;
use Base\Models\Quantity;
use Base\Models\Category;
use Base\Models\FoodItem;
use Base\Models\Recipe;
use Base\Models\Ingredient;
use Base\Models\Meal;
use Base\Models\GroceryListItem;

use Base\Factories\UserFactory;
use Base\Factories\HouseholdFactory;
use Base\Factories\UnitFactory;
use Base\Factories\QuantityFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\RecipeFactory;
use Base\Factories\IngredientFactory;
use Base\Factories\MealFactory;
use Base\Factories\GroceryListItemFactory;

use Base\Repositories\HouseholdRepository;
use Base\Repositories\CategoryRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\IngredientRepository;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\RecipeRepository;

class IntegrationTest extends TestCase {
    // Variables to be reused
    private $user,
            $household,
            $unit,
            $qty,
            $category,
            $food,
            $recipe,
            $ingredient,
            $meal,
            $groceryItem,
            $userFty,
            $householdFty,
            $unitFty,
            $qtyFty,
            $categoryFty,
            $foodFty,
            $recipeFty,
            $ingredientFty,
            $mealFty,
            $groceryItemFty;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      ////////////////////////////////////////////////////////////////////////
        // Setup User Factory //
    	////////////////////////////////////////////////////////////////////////
      $hhRepoStub = $this->createMock(HouseholdRepository::class);
      $hhStub = $this->createMock(Household::class);
      $hhsArray = array($hhStub);
      $hhRepoStub->method('allForUser')->will($this->returnValue($hhsArray));
      $hhRepoStub->method('find')->will($this->returnValue($hhStub));
      $this->userFty = new UserFactory($hhRepoStub);
      $userArray = array(
          'id' => 99999,
          'username' => 'jsmith',
          'password' => 'b9c73a5ddae499143c1031d9a1ff0f34f90d9d346df83075583c56bcbc9a5675',
          'email' => 'john.smith@example.com',
          'joined' => '2018-01-01 12:00:00',
          'namefirst' => 'John',
          'namelast' => 'Smith',
          'activated' => 1,
          'passTemp' => NULL,
          'currHouseholdId' => 1,
          'profilePic' => 'hsdfyugds78fsdfuile4r83rudsgfjkf.png'
      );
      $this->user = $this->userFty->make($userArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup Household Factory //
    	////////////////////////////////////////////////////////////////////////
      $this->householdFty = new HouseholdFactory();
      ////////////////////////////////////////////////////////////////////////
        // Setup Unit Factory //
    	////////////////////////////////////////////////////////////////////////
      $this->unitFty = new UnitFactory();
      $unitArray = array('id' => 2,
                         'name' => 'piece',
                         'baseEqv' => 1,
                         'baseUnit' => 'piece',
                         'abbreviation' => 'pc');
      $this->unit = $this->unitFty->make($unitArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup Quantity //
    	////////////////////////////////////////////////////////////////////////
      $this->qty = new Quantity(10,$this->unit);
      ////////////////////////////////////////////////////////////////////////
        // Setup Category Factory //
    	////////////////////////////////////////////////////////////////////////
      $this->categoryFty = new CategoryFactory();
      $catArray = array('id' => 4,
                        'name' => 'Grains');
      $this->category = $this->categoryFty->make($catArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup Food Factory //
    	////////////////////////////////////////////////////////////////////////
      $categoryRepoStub = $this->createMock(CategoryRepository::class);
      $categoryRepoStub->method('find')->will($this->returnValue($this->category));
      $unitRepoStub = $this->createMock(UnitRepository::class);
      $unitRepoStub->method('find')->will($this->returnValue($this->unit));

      $this->foodFty = new FoodItemFactory($categoryRepoStub, $unitRepoStub);

      $foodArray = array('id' => 32423,
                         'name' => 'Bread',
                         'stock' => 20,
                         'unitId' => 2,
                         'categoryId' => 4,
                         'unitsInContainer' => 20,
                         'containerCost' => 1.99);
      $this->food = $this->foodFty->make($foodArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup Ingredient Factory //
    	////////////////////////////////////////////////////////////////////////
      $foodRepoStub = $this->createMock(FoodItemRepository::class);
      $foodRepoStub->method('find')->will($this->returnValue($this->food));
      $this->ingredientFty = new IngredientFactory($foodRepoStub, $unitRepoStub);

      $ingrArray = array('foodId' => 32423,
                         'unitId' => 2,
                         'quantity' => 2,
                         'recipeId' => 5);
      $this->ingredient = $this->ingredientFty->make($ingrArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup Recipe Factory //
    	////////////////////////////////////////////////////////////////////////
      $ingrRepoStub = $this->createMock(IngredientRepository::class);
      $recIngrArray = array($this->ingredient);
      $ingrRepoStub->method('allForRecipe')->will($this->returnValue($recIngrArray));
      $this->recipeFty = new RecipeFactory($ingrRepoStub);

      $recipeArray = array('id' => 5,
                           'name' => 'PB&J',
                           'directions'=> '1) Spread PB, 2) Spread Jelly, 3) Put together',
                           'servings'=> 1,
                           'source'=> '',
                           'notes'=> '');
      $this->recipe = $this->recipeFty->make($recipeArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup Meal Factory //
    	////////////////////////////////////////////////////////////////////////
      $recipeRepoStub = $this->createMock(RecipeRepository::class);
      $recipeRepoStub->method('find')->will($this->returnValue($this->recipe));
      $this->mealFty = new MealFactory($recipeRepoStub);

      $mealArray = array('recipeId' => 5,
                         'date' => '2018-01-01',
                         'scaleFactor' => 4);
      $this->meal = $this->mealFty->make($mealArray);
      ////////////////////////////////////////////////////////////////////////
        // Setup GroceryListItem Factory //
    	////////////////////////////////////////////////////////////////////////
      $this->groceryItemFty = new GroceryListItemFactory($foodRepoStub);

      $groceryArray = array('id' => 9934245,
                            'foodItemId' => 32423,
                            'amount' => 10);
      $this->groceryItem = $this->groceryItemFty->make($groceryArray);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->user);
      unset($this->household);
      unset($this->unit);
      unset($this->qty);
      unset($this->category);
      unset($this->food);
      unset($this->ingredient);
      unset($this->recipe);
      unset($this->meal);
      unset($this->groceryItem);

      unset($this->userFty);
      unset($this->householdFty);
      unset($this->unitFty);
      unset($this->qtyFty);
      unset($this->categoryFty);
      unset($this->foodFty);
      unset($this->ingredientFty);
      unset($this->recipeFty);
      unset($this->mealFty);
      unset($this->groceryItemFty);
    }

    public function testUserFactoryIntegrate(){
      $this->assertInstanceOf(
            'Base\Factories\UserFactory',
            $this->userFty,
            'Object must be instance of UserFactory');
      $this->assertEquals($this->user->getId(),99999);
    }

    public function testHouseholdFactoryIntegrate(){
      $hhArray = array('id' => 564356,
                       'name' => 'Test Household',
                       'owner' => $this->user->getUsername());
      $this->household = $this->householdFty->make($hhArray);
      $this->user->setHouseholds([$this->household]);
      $this->user->setCurrHousehold($this->household);

      $this->assertEquals($this->user->getCurrHousehold()->getId(), 564356);
      $this->assertEquals($this->user->getHouseholds()[0]->getId(), 564356);
      $this->assertEquals($this->household->getOwner(), 'jsmith');
    }

    public function testUnitFactoryIntegrate(){
      $this->assertEquals($this->unit->getId(),2);
    }

    public function testCategoryFactoryIntegrate(){
      $this->assertEquals($this->category->getId(),4);
    }

    public function testFoodFactoryIntegrate(){
      $this->assertEquals($this->food->getId(),32423);
      $this->assertEquals($this->food->getUnit()->getBaseEqv(),1);
      $this->assertEquals($this->food->getCategory()->getId(),4);
    }

    public function testIngredientFactory(){
      $this->assertEquals($this->ingredient->getFood()->getId(), 32423);
      $this->assertEquals($this->ingredient->getUnit()->getId(), 2);
      $this->assertEquals($this->ingredient->getQuantity()->getValue(), 2);
    }

    public function testRecipeFactory(){
      $this->assertEquals($this->recipe->getName(), 'PB&J');
      $this->assertEquals($this->recipe->getIngredients()[0]->getQuantity()->getValue(),2);
      $this->assertEquals($this->recipe->getIngredientByName('Bread')->getFood()->getId(),32423);
    }

    public function testMealFactoryIntegrate(){
      $this->assertEquals($this->meal->getScaleFactor(),4);
      $this->assertEquals($this->meal->getRecipe()->getName(),'PB&J');
    }

    public function testGroceryIntegrate(){
      $this->assertEquals($this->groceryItem->getAmount(),10);
      $this->assertEquals($this->groceryItem->getFoodItem()->getId(),32423);
    }
}
