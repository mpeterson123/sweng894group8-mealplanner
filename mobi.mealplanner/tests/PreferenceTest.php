<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                              Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Preference Test
///////////////////////////////////////////////////////////////////////////////
namespace App\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use App\Preference;

class PreferenceTest extends TestCase
{
    // Variables to be reused
    private $preference;

    //
    // Create instances or whatever you need to reuse in several tests here
    //
    public function setUp()
    {
        $this->preference = new Preference();
    }

    //
    // Unset any variables you've created
    //
    public function tearDown()
    {
        unset($this->preference);
    }

    public function testUpdatePreferenceName()
    {
        $this->preference->updateName('testName');
        $this->assertEquals($this->preference->getName(), 'testName', 'Name must match supplied value');
    }

    public function testUpdatePreferenceValue()
    {
        $this->preference->updateValue('testValue');
        $this->assertEquals($this->preference->getValue(), 'testValue', 'Value must match supplied value');
    }

    public function testDeletePreference()
    {
        $this->preference->delete();
        $this->assertNull($this->preference->getName(), 'Preference cannot exist.');
    }

}

?>
