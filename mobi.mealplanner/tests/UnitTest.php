<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Unit Test
///////////////////////////////////////////////////////////////////////////////
namespace App\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use App\Unit;

class UnitTest extends TestCase
{
    //
    // Variables to be reused
    //
    private $unit;

    //
    // Create instances or whatever you need to reuse in several tests here
    //
    public function setUp()
    {
        $this->unit = new Unit();
    }

    //
    // Unset any variables you've created
    //
    public function tearDown()
    {
        unset($this->unit);
    }

    public function testUpdateUnitName()
    {
        $this->unit->updateName('testName');
        $this->assertEquals($this->unit->getName(), 'testName', 'Name must match supplied value');
    }

    public function testDeleteUnit()
    {
        $this->unit->delete();
        $this->assertNull($this->unit->getName(), 'Unit cannot exist.');
    }

}

?>
