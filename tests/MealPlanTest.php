<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/MealPlan.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\MealPlan;


class MealPlanTest extends TestCase {
    // Variables to be reused
    private $mealPlan,
      $start,
      $end;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */


    public function setUp(){
      $this->start=('9/10/2018');
      $this->end=('9/17/2018');
      $this->mealPlan = new MealPlan($this->start, $this->end);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->mealPlan);
    }

    public function testCreateMealPlan(){
      $this->assertInstanceOf(MealPlan::class, $this->mealPlan, 'Object must be instance of MealPlan.');
    }

    public function testConstructStartDate() {
      $this->assertEquals($this->start, $this->mealPlan->getStartDate(), 'Start date wasn\'t set up properly in constructor.');
    }

    public function testConstructEndDate() {
      $this->assertEquals($this->end, $this->mealPlan->getEndDate(), 'End date wasn\'t set up properly in constructor.');
    }

    public function testSetStartDate(){
      $sDate = ('9/17/2018');
      $this->mealPlan->setStartDate($sDate);
    	$this->assertEquals($sDate, $this->mealPlan->getStartDate(), '');
    }

    public function testSetEndDate(){
      $eDate = ('9/24/2018');
      $this->mealPlan->setEndDate($eDate);
    	$this->assertEquals($eDate, $this->mealPlan->getEndDate(), '');
    }

}
