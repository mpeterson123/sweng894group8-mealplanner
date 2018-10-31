<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use Base\Models\Household;


class HouseholdTest extends TestCase {
	// Variables to be reused
	private $household;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
		$this->household = new Household();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->household);
    }

	///////////////////
	// Instatiation //
	//////////////////
	public function testCreateHousehold(){
    	$this->assertInstanceOf(
            'Base\Models\Household',
            new Household(),
            'Object must be an instance of Household');
    }

	////////
	// Id //
	////////
	public function testSetId(){
		$id = 9999;
        $this->household->setId($id);
        $this->assertEquals($this->household->getId(), $id);
    }

    ///////////////
    // Name //
    //////////////
    public function testSetName(){
        $this->household->setName('anotherHousehold');
        $this->assertEquals($this->household->getName(), 'anotherHousehold');
    }
    public function testNameCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->household->setName('');
    }

		///////////////
    // Owner //
    //////////////
    public function testSetOwner(){
        $this->household->setOwner('theNewOwner');
        $this->assertEquals($this->household->getOwner(), 'theNewOwner');
    }
    public function testOwnerCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->household->setOwner('');
    }




	////////////////////////////////////////////////////////////////////////////
	// Actions
	////////////////////////////////////////////////////////////////////////////


}
