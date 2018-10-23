<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\UnitFactory;
use Base\Models\Unit;


class UnitFactoryTest extends TestCase {
    // Variables to be reused
    private $unitFactory;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        $this->unitFactory = new UnitFactory();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->unitFactory);
    }

    public function testCreateUnitFactory(){
    	$this->assertInstanceOf(
            'Base\Factories\UnitFactory',
            new UnitFactory(),
            'Object must be instance of UnitFactory');
    }

    public function testMakeUnitWithId(){
        $unitArray = array(
            'id' => 3452,
            'name' => 'millimeter(s)',
            'abbreviation' => 'mm',
            'baseUnit' => 'm',
            'baseEqv' => 0.001
        );

        $unit = $this->unitFactory->make($unitArray);
    	$this->assertInstanceOf(
            'Base\Models\Unit',
            $unit,
            'Object must be instance of Unit');

        $this->assertEquals($unit->getId(), $unitArray['id']);
        $this->assertEquals($unit->getName(), $unitArray['name']);
        $this->assertEquals($unit->getAbbreviation(), $unitArray['abbreviation']);
        $this->assertEquals($unit->getBaseUnit(), $unitArray['baseUnit']);
        $this->assertEquals($unit->getBaseEqv(), $unitArray['baseEqv']);
    }

    public function testMakeUnitWithoutId(){
        $unitArray = array(
            'name' => 'millimeter(s)',
            'abbreviation' => 'mm',
            'baseUnit' => 'm',
            'baseEqv' => 0.001
        );

        $unit = $this->unitFactory->make($unitArray);
    	$this->assertInstanceOf(
            'Base\Models\Unit',
            $unit,
            'Object must be instance of Unit');

        $this->assertEquals($unit->getId(), NULL);
        $this->assertEquals($unit->getName(), $unitArray['name']);
        $this->assertEquals($unit->getAbbreviation(), $unitArray['abbreviation']);
        $this->assertEquals($unit->getBaseUnit(), $unitArray['baseUnit']);
        $this->assertEquals($unit->getBaseEqv(), $unitArray['baseEqv']);
    }
}
