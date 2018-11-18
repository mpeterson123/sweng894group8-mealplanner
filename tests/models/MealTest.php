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

	////////////////////////////////////////////////////////////////////////////
    // Instatiation //
	////////////////////////////////////////////////////////////////////////////

    public function testCreateMeal(){
      $this->assertInstanceOf('Base\Models\Meal',
        new Meal($this->recipe,date("Y-m-d H:i:s"),2.0),
        'Object must be an instance of Meal');
    }

	////////////////////////////////////////////////////////////////////////////
    // Id  //
	////////////////////////////////////////////////////////////////////////////
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

    public function testIdCannotBeNegative(){
        $negativeId = -1;
        $this->expectException(\Exception::class);
        $this->meal->setId($negativeId);
    }

    public function testIdCannotBeZero(){
        $zeroId = 0;
        $this->expectException(\Exception::class);
        $this->meal->setId($zeroId);
    }


	////////////////////////////////////////////////////////////////////////////
    // ScaleFactor //
	////////////////////////////////////////////////////////////////////////////

    public function testEditMealScaleFactor(){
        $scaleFactor = 1.5;
        $this->meal->setScaleFactor($scaleFactor);
        $this->assertEquals($this->meal->getScaleFactor(), $scaleFactor);
    }

    public function testScaleFactorIsStoredAsFloat(){
        $floatScaleFactor = '123.45';
        $this->meal->setScaleFactor($floatScaleFactor);
        $this->assertInternalType('float', $this->meal->getScaleFactor());
    }

    public function testEmptyScaleFactorReturns1(){
        $this->meal->setScaleFactor(NULL);
        $this->assertEquals($this->meal->getScaleFactor(), 1.00);

        $this->meal->setScaleFactor('');
        $this->assertEquals($this->meal->getScaleFactor(), 1.00);

        $this->meal->setScaleFactor(false);
        $this->assertEquals($this->meal->getScaleFactor(), 1.00);
    }

    /**
     * @dataProvider belowOneNumberProvider
     */
    public function testNegativeScaleFactorsReturn1($negativeScaleFactor){
        $this->meal->setScaleFactor($negativeScaleFactor);
        $this->assertEquals($this->meal->getScaleFactor(), 1.00);
    }

    public function belowOneNumberProvider()
    {
        return [
            'zero' => [0],
            'negative one' => [-1],
            'long negative number' => [-999999999],
            'very small number' => [-0.0000000000000000000000000000000000000001],
        ];
    }

    public function testNumberLookAlikeAScaleFactorReturnsFloatNumber(){
        $scaleFactor = "50.";
        $this->meal->setScaleFactor($scaleFactor);
        $this->assertEquals($this->meal->getScaleFactor(), 50.0);
    }

    public function testNonNumericScaleFactorReturns1(){
        $scaleFactor = "...50.";
        $this->meal->setScaleFactor($scaleFactor);
        $this->assertEquals($this->meal->getScaleFactor(), 1.00);
    }

    /**
     * @dataProvider tooHighScaleFactorProvider
     */
    public function testScaleFactorCannotExceedMax($scaleFactor){
        $this->meal->setScaleFactor($scaleFactor);
        $this->assertEquals($this->meal->getScaleFactor(), 500.0);
    }

    public function tooHighScaleFactorProvider()
    {
        return [
            '500 01' => [500.01],
            '1000' => [1000],
            'Too high integer' => [10000000000000000000000000000],
            'Too high decimal' => [100000.5325]
        ];
    }

	////////////////////////////////////////////////////////////////////////////
    // Date //
	////////////////////////////////////////////////////////////////////////////

    public function testEditMealDate(){
      $date = date("Y-m-d");
      $this->meal->setDate($date);
      $this->assertEquals($this->meal->getDate(), $date);
    }

    public function testRejectInvalidDate(){
      $this->expectException(\Exception::class);
      $this->meal->setDate('bad');//"Date must be a timestamp"
    }

    public function testGetDateFormatted(){
        $date = "2018-12-12";
        $formattedDate = "12/12/2018";
        $this->meal->setDate($date);
        $this->assertEquals($this->meal->getDate(true), $formattedDate);
    }

	////////////////////////////////////////////////////////////////////////////
    // Recipe //
	////////////////////////////////////////////////////////////////////////////
    public function testGetRecipe(){
        $recipe = $this->createMock(Recipe::class);
        $this->meal->setRecipe($recipe);
        $this->assertEquals($this->meal->getRecipe(), $recipe);
    }

    public function testRecipeIsOfTypeRecipe(){
        $recipe = $this->createMock(Recipe::class);
        $this->meal->setRecipe($recipe);
        $this->assertEquals($this->meal->getRecipe(), $recipe);

        $this->assertInstanceOf(
            'Base\Models\Recipe',
            $recipe,
            'Object must be instance of Recipe');
    }

    public function testRejectInvalidRecipe(){
        $this->expectException(\Exception::class);
        $this->meal->setRecipe('bad');//"Meal must reference a Recipe"
    }

	////////////////////////////////////////////////////////////////////////////
    // Complete //
	////////////////////////////////////////////////////////////////////////////

    public function testMarkMealCompleted(){
      $this->meal->complete();
      $this->assertTrue($this->meal->isComplete(), 'Recipe must be completed.');
      $this->assertNotNull($this->meal->getCompletedOn(), 'Recipe must have a completion date.');
    }

	////////////////////////////////////////////////////////////////////////////
    // CompletedOn //
	////////////////////////////////////////////////////////////////////////////
    public function testGetCompletedOn(){
      $completedOn = date("Y-m-d H:i:s");
      $this->meal->setCompletedOn($completedOn);
      $this->assertEquals($this->meal->getCompletedOn(), $completedOn);
    }

    public function testRejectInvalidCompletedOn(){
      $this->expectException(\Exception::class);
      $this->meal->setCompletedOn('bad');//"Date must be a timestamp"
    }

    public function testGetCompletedOnFormatted(){
        $completedOn = "2018-12-12 13:02:03";
        $formattedDate = "12/12/2018, 01:02 PM";
        $this->meal->setCompletedOn($completedOn);
        $this->assertEquals($this->meal->getCompletedOn(true), $formattedDate);
    }

	////////////////////////////////////////////////////////////////////////////
    // AddedDate //
	////////////////////////////////////////////////////////////////////////////

    public function testGetAddedDate(){
        $addedDate = date("Y-m-d H:i:s");
        $this->meal->setAddedDate($addedDate);
        $this->assertEquals($this->meal->getAddedDate(), $addedDate);
    }

    public function testRejectInvalidAddedDate(){
        $this->expectException(\Exception::class);
        $this->meal->setAddedDate('bad');//"Date must be a timestamp"
    }

    public function testGetAddedDateFormatted(){
        $addedDate = "2018-12-12 13:02:03";
        $formattedDate = "12/12/2018, 01:02 PM";
        $this->meal->setAddedDate($addedDate);
        $this->assertEquals($this->meal->getAddedDate(true), $formattedDate);
    }

}
?>
