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
            $groceryItem;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      // Setup User
      $this->user = new User();
      $this->user->setUsername('tuser');
      $this->user->setFirstName('Test');
      $this->user->setLastName('User');
      $this->user->setEmail('tuser@domain.com');
      $this->user->setId(99999);
      // Setup Household
      $this->household = new Household();
      // Setup Unit
      $this->unit = array();
      $this->unit['dozen'] = new Unit();
      $this->unit['dozen']->setId(9);
      $this->unit['dozen']->setName('Dozen');
      $this->unit['dozen']->setBaseEqv(12);
      $this->unit['dozen']->setBaseUnit('piece');
      $this->unit['piece'] = new Unit();
      $this->unit['piece']->setId(2);
      $this->unit['piece']->setName('Piece');
      $this->unit['piece']->setBaseEqv(1);
      $this->unit['piece']->setBaseUnit('piece');
      // Setup Quantity
      $this->qty = new Quantity(2,$this->unit['dozen']);
      // Setup Category
      $this->category = new Category();
      $this->category->setId(4);
      $this->category->setName('Grains');
      // Setup Food
      $this->food = new FoodItem();
      $this->food->setId(32423);
      $this->food->setName('Bread');
      $this->food->setStock(20);
      $this->food->setUnit($this->unit['piece']);
      $this->food->setCategory($this->category);
      // Setup Recipe and Ingredient
      $this->recipe = new Recipe('PB&J','1) Spread PB, 2) Spread Jelly, 3) Put together',1);
      $this->ingredient = new Ingredient($this->food, 2, 5, $this->unit['piece']);
      $this->recipe->addIngredient($this->ingredient);
      // Setup Meal
      $this->meal = new Meal();
      $this->meal->setId(43);
      $this->meal->setRecipe($this->recipe);
      $this->meal->setScaleFactor(4);
      // Setup GroceryItem
      $this->groceryItem = new GroceryListItem();
      $this->groceryItem->setId(34245);
      $this->groceryItem->setFoodItem($this->food);
      $this->groceryItem->setAmount(10);
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
    }

    public function testUserAndHouseholdIntegrate(){
      $this->household->setId(564356);
      $this->household->setName('Test Household');
      $this->user->setHouseholds([$this->household]);
      $this->household->setOwner($this->user->getUsername());
      $this->user->setCurrHousehold($this->household);

      $this->assertEquals($this->user->getCurrHousehold()->getId(), 564356);
      $this->assertEquals($this->user->getHouseholds()[0]->getId(), 564356);
      $this->assertEquals($this->household->getOwner(), 'tuser');
    }

    public function testQuantityIntegrate(){
      $this->assertEquals($this->qty->getUnit()->getId(),9);
      $this->qty->convertTo($this->unit['piece']);
      $this->assertEquals($this->qty->getUnit()->getId(),2);
      $this->assertEquals($this->qty->getValue(),24);
    }

    public function testFoodIntegrate(){
      $this->assertEquals($this->food->getUnit()->getBaseEqv(),1);
      $this->assertEquals($this->food->getCategory()->getId(),4);
    }

    public function testRecipeAndIngredientIntegrate(){
      $this->assertEquals($this->recipe->getIngredientByName('Bread')->getFood()->getId(),32423);
    }

    public function testMealIntegrate(){
      $this->assertEquals($this->meal->getRecipe()->getName(),'PB&J');
    }

    public function testGroceryIntegrate(){
      $this->assertEquals($this->groceryItem->getFoodItem()->getId(),32423);
    }
}
