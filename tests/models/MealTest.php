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
    private $recipe;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->recipe = new Recipe('Sugar Cookies','Sugar Cookies',6);
      $this->meal = new Meal($this->recipe,date("Y-m-d H:i:s"),1.0);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->meal);
      unset($this->recipe);
    }

    public function testCreateMeal(){
      $this->assertInstanceOf('Base\Models\Meal',
        new Meal($this->recipe,date("Y-m-d H:i:s"),2.0),
        'Object must be an instance of Meal');
    }

    public function testEditMealScale(){
      $this->meal->setScale(1.5);
      $this->assertEquals($this->meal->getScale(), 1.5);
    }

    public function testEditMealDate(){
      $nowTime = date("Y-m-d H:i:s");
      $this->meal->setDate($nowTime);
      $this->assertEquals($this->meal->getDate(), $nowTime);
    }

    public function testEditMealRecipe(){
      //$newRecipe = 1;
      $this->meal->setRecipe($this->recipe);
      $this->assertEquals($this->meal->getRecipe(), $this->recipe);
    }

    public function testRejectInvalidScale(){
      $this->expectException(\Exception::class);
      $this->meal->setScale('bad');//"Id must be a number"
    }

    public function testRejectInvalidDate(){
      $this->expectException(\Exception::class);
      $this->meal->setDate('bad');//"Date must be a timestamp"
    }

    public function testRejectInvalidRecipe(){
      $this->expectException(\Exception::class);
      $this->meal->setRecipe('bad');//"Meal must reference a Recipe"
    }

    public function testMarkMealCompleted(){
      $this->meal->complete();
      $this->assertTrue($this->meal->isComplete(), 'Recipe must be completed.');
    }

    //public function testDeleteMeal(){
    //  $this->meal->delete();
    //}

}
?>
