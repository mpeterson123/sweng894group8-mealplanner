<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\HouseholdFactory;
use Base\Models\Household;


class HouseholdFactoryTest extends TestCase {
    // Variables to be reused
    private $householdFactory;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        $this->householdFactory = new HouseholdFactory();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->householdFactory);
    }

    public function testCreateHouseholdFactory(){
    	$this->assertInstanceOf(
            'Base\Factories\HouseholdFactory',
            new HouseholdFactory(),
            'Object must be instance of HouseholdFactory');
    }

    public function testMakeHouseholdWithId(){
        $householdArray = array(
            'id' => 1234,
            'name' => 'Doe Household',
        );

        $household = $this->householdFactory->make($householdArray);
    	$this->assertInstanceOf(
            'Base\Models\Household',
            $household,
            'Object must be instance of Household');

        $this->assertEquals($household->getId(), $householdArray['id']);
        $this->assertEquals($household->getName(), $householdArray['name']);
    }

    public function testMakeHouseholdWithoutId(){
        $householdArray = array(
            'name' => 'Doe Household',
        );

        $household = $this->householdFactory->make($householdArray);
    	$this->assertInstanceOf(
            'Base\Models\Household',
            $household,
            'Object must be instance of Household');

        $this->assertEquals($household->getId(), NULL);
        $this->assertEquals($household->getName(), $householdArray['name']);
    }
}
