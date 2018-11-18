<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use Base\Models\Household;


class HouseholdTest extends TestCase {
	// Owners to be reused
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

	////////////////////////////////////////////////////////////////////////////
	// Instatiation //
	////////////////////////////////////////////////////////////////////////////
	public function testCreateHousehold(){
    	$this->assertInstanceOf(
            'Base\Models\Household',
            new Household(),
            'Object must be an instance of Household');
    }

	////////////////////////////////////////////////////////////////////////////
	// Id //
	////////////////////////////////////////////////////////////////////////////
	public function testSetAndGetId(){
	    $id = 1;
	    $this->household->setId($id);
	    $this->assertEquals($this->household->getId(), $id);
	}

	public function testIdCannotBeEmpty(){
	    $this->expectException(\Exception::class);
	    $this->household->setId(NULL);
	}

	public function testIdIsAnInteger(){
	    $intId = 123;
	    $this->household->setId($intId);
	    $this->assertInternalType('integer', $this->household->getId());
	}

	public function testIdCannotBeNegative(){
        $negativeId = -1;
        $this->expectException(\Exception::class);
        $this->household->setId($negativeId);
    }

    public function testIdCannotBeZero(){
        $zeroId = 0;
        $this->expectException(\Exception::class);
        $this->household->setId($zeroId);
    }

	////////////////////////////////////////////////////////////////////////////
    // Name //
	////////////////////////////////////////////////////////////////////////////
	public function testSetName(){
	    $variable = 'A Household';
	    $this->household->setName($variable);
	    $this->assertEquals($this->household->getName(), $variable);
	}

	public function testNameCannotBeEmpty(){
	    $this->expectException(\Exception::class);
	    $this->household->setName('');
	}

	public function testNameCannotBeLongerThan50Chars(){
	    $longName = '123456789012345678901234567890123456789012345678901';
	    $this->expectException(\Exception::class);
	    $this->household->setName($longName);
	}

	public function testNameCannotHaveExtraWhitespace(){
	    $variableWithWhitespace = ' A Household   ';
	    $expectedName =  'A Household';
	    $this->household->setName($variableWithWhitespace);

	    $this->assertEquals($this->household->getName(), $expectedName,
	        'Name must be trimmed.');
	}

	public function testNameIsString(){
	    $stringName = 'Household';
	    $this->household->setName($stringName);
	    $this->assertInternalType('string', $stringName);
	}

	public function testNonStringNamesAreRejected(){
	    $nonStringName = 0;
	    $this->expectException(\Exception::class);
	    $this->household->setName($nonStringName);
	}

	////////////////////////////////////////////////////////////////////////////
    // Owner //
	////////////////////////////////////////////////////////////////////////////
	public function testSetOwner(){
	    $variable = 'AnOwner';
	    $this->household->setOwner($variable);
	    $this->assertEquals($this->household->getOwner(), $variable);
	}

	public function testOwnerCannotBeEmpty(){
	    $this->expectException(\Exception::class);
	    $this->household->setOwner('');
	}

	public function testOwnerCannotBeLongerThan32Chars(){
	    $longOwner = '123456789012345678901234567890123';
	    $this->expectException(\Exception::class);
	    $this->household->setOwner($longOwner);
	}

	public function testOwnerCannotHaveExtraWhitespace(){
	    $variableWithWhitespace = ' AnOwner   ';
	    $expectedOwner =  'AnOwner';
	    $this->household->setOwner($variableWithWhitespace);

	    $this->assertEquals($this->household->getOwner(), $expectedOwner,
	        'Owner must be trimmed.');
	}

	public function testOwnerIsString(){
	    $stringOwner = 'Owner';
	    $this->household->setOwner($stringOwner);
	    $this->assertInternalType('string', $stringOwner);
	}

	public function testNonStringOwnersAreRejected(){
	    $nonStringOwner = 0;
	    $this->expectException(\Exception::class);
	    $this->household->setName($nonStringOwner);
	}

	public function testNonAlphanumericOwnerIsRejected(){
		$invalidOwner = 'A *bad*_owner!';
		$this->expectException(\Exception::class);
        $this->household->setOwner($invalidOwner);
	}

	////////////////////////////////////////////////////////////////////////////
	// Actions
	////////////////////////////////////////////////////////////////////////////

	/**
	 * @dataProvider idForInviteCodeProvider
	 */
	public function testGenCode($id){
	  	$this->household->setId($id);
		$code = $this->household->genInviteCode();
		$this->assertEquals($this->household->reverseCode($code), $id);
	}

	public function idForInviteCodeProvider()
	{
	    return [
			'4' => [4],
			'2' => [2],
			'100' => [100],
			'153' => [153],
			'1' => [1],
	        '9999999999999999' => [9999999999999999],
	    ];
	}

}
