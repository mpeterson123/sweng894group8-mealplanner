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


    /////////
    // Id  //
    /////////
    public function testSetAndGetId(){
        $id = 1;
        $this->meal->setId($id);
        $this->assertEquals($this->meal->getId(), $id);
    }

    public function testIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->meal->setId(NULL);
    }

    public function testIdIsAnInteger(){
        $intId = 123;
        $this->meal->setId($intId);
        $this->assertInternalType('integer', $this->meal->getId());
    }

    public function testNonIntIdIsRejected(){
        $nonIntId = '123';
        $this->expectException(\Exception::class);
        $this->meal->setId($nonIntId);
    }


    public function testCreateMeal(){
      $this->assertInstanceOf('Base\Models\Meal',
        new Meal($this->recipe,date("Y-m-d H:i:s"),2.0),
        'Object must be an instance of Meal');
    }

    public function testEditMealScaleFactor(){
      $this->meal->setScaleFactor(1.5);
      $this->assertEquals($this->meal->getScaleFactor(), 1.5);
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

    public function testRejectInvalidScaleFactor(){
      $this->meal->setScaleFactor('bad');//"Id must be a number"
      $this->assertEquals($this->meal->getScaleFactor(), 1.0);

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

}
?>
