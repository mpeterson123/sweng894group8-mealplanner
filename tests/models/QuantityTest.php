<?php

namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

// Add the classes you are testing
use Base\Models\Quantity;
use Base\Models\Unit;

class QuantityTest extends TestCase {
    // Variables to be reused
    private $units;
    private $quantity;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        $this->quantity = new Quantity(1, $this->units['mL']);

        $u = $this->makeUnitStub('milliliter(s)', 'mL', 1);
        $this->units['mL'] = $u;

        $u = $this->makeUnitStub('liter(s)', 'mL', 1000);
        $this->units['L'] = $u;

        $u = $this->makeUnitStub('teaspoon(s)', 'mL', 4.9289215);
        $this->units['tsp'] = $u;

        $u = $this->makeUnitStub('tablespoon(s)', 'mL', 14.786765);
        $this->units['Tbsp'] = $u;

        $u = $this->makeUnitStub('pound(s)', 'g', 453.592368);
        $this->units['lb'] = $u;
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->units);
      unset($this->quantity);
    }


	////////////////////////////////////////////////////////////////////////////
    // Instantiation //
	////////////////////////////////////////////////////////////////////////////
    public function testCreateQuantity(){
    	$this->assertInstanceOf(
            'Base\Models\Quantity',
            new Quantity(1, $this->units['mL']),
            'Object must be instance of Quantity');
    }


	////////////////////////////////////////////////////////////////////////////
    // ConvertTo //
	////////////////////////////////////////////////////////////////////////////

    public function testConvertTo_L_mL(){
      $quantity = new Quantity(2.5,$this->units['L']);

      $quantity->convertTo($this->units['mL']);

      $this->assertEquals($quantity->getValue(),2500);
      $this->assertEquals($quantity->getUnit()->getName(),'milliliter(s)');
    }

    public function testConvertTo_tsp_Tbsp(){
      $quantity = new Quantity(12,$this->units['tsp']);

      $quantity->convertTo($this->units['Tbsp']);

      $this->assertEquals($quantity->getValue(),4,'',0.0001);
      $this->assertEquals($quantity->getUnit()->getName(),'tablespoon(s)');
    }

    public function testIncompatibleUnitsAreRejected(){
      $quantity = new Quantity(12,$this->units['tsp']);

      $this->expectException(\Exception::class);
      $quantity->convertTo($this->units['lb']);
    }

	////////////////////////////////////////////////////////////////////////////
    // Value //
	////////////////////////////////////////////////////////////////////////////

    public function testGetValue(){
        $stock = 3;
        $this->quantity->setValue($stock);
        $this->assertEquals($this->quantity->getValue(), $stock, 'Value must be ${stock}.');
    }

    public function testValueIsStoredAsFloat(){
        $floatValue = '123.45';
        $this->quantity->setValue($floatValue);
        $this->assertInternalType('float', $this->quantity->getValue());
    }

    /**
     * @dataProvider tooLowValueProvider
     */
    public function testValueCannotBeZeroOrNegative($variable){
        $this->expectException(\Exception::class);
        $this->quantity->setValue($variable);
    }

    public function tooLowValueProvider()
    {
        return [
            'zero' => [0],
            'negative one' => [-1],
            'long negative number' => [-999999999],
            'very small number' => [-0.0000000000000000000000000000000000000001]
        ];
    }

	////////////////////////////////////////////////////////////////////////////
    // Unit //
	////////////////////////////////////////////////////////////////////////////

    public function testGetUnit(){
        $unit = $this->createMock(Unit::class);
        $this->quantity->setUnit($unit);
        $this->assertEquals($this->quantity->getUnit(), $unit);
    }

    public function testUnitIsOfTypeUnit(){
        $unit = $this->createMock(Unit::class);
        $this->quantity->setUnit($unit);
        $this->assertEquals($this->quantity->getUnit(), $unit);

        $this->assertInstanceOf(
            'Base\Models\Unit',
            $unit,
            'Object must be instance of Unit');
    }

    public function testRejectInvalidUnit(){
        $this->expectException(\Exception::class);
        $this->quantity->setUnit('bad');
    }

	////////////////////////////////////////////////////////////////////////////
    // MakeUnitStub //
	////////////////////////////////////////////////////////////////////////////
    private function makeUnitStub($name, $baseUnit, $baseEqv){
        $unitStub = $this->createMock(Unit::class);
        $unitStub->method('getName')->willReturn($name);
        $unitStub->method('getBaseUnit')->willReturn($baseUnit);
        $unitStub->method('getBaseEqv')->willReturn($baseEqv);
        return $unitStub;
    }

}
?>
