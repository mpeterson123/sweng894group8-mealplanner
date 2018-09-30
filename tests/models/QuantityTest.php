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
    private $qty;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $u = new Unit();
      $u->setName('milliliter(s)');
      $u->setBaseEqv(1);
      $this->units['mL'] = $u;

      $u = new Unit();
      $u->setName('liter(s)');
      $u->setBaseEqv(1000);
      $this->units['L'] = $u;

      $u = new Unit();
      $u->setName('teaspoon(s)');
      $u->setBaseEqv(4.9289215);
      $this->units['tsp'] = $u;

      $u = new Unit();
      $u->setName('tablespoon(s)');
      $u->setBaseEqv(14.786765);
      $this->units['tbsp'] = $u;
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->units);
      unset($this->qty);
    }
    public function testConvertTo_L_mL(){
      $qty = new Quantity(2.5,$this->units['L']);

      $qty->convertTo($this->units['mL']);

      $this->assertEquals($qty->getValue(),2500);
      $this->assertEquals($qty->getUnit()->getName(),'milliliter(s)');
    }
    public function testConvertTo_tsp_tbsp(){
      $qty = new Quantity(12,$this->units['tsp']);

      $qty->convertTo($this->units['tbsp']);

      $this->assertEquals($qty->getValue(),4,'',0.0001);
      $this->assertEquals($qty->getUnit()->getName(),'tablespoon(s)');
    }
}
?>
