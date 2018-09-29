<?php

namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\Ingredient;


class IngredientTest extends TestCase {
    // Variables to be reused
    private $ingredient,
      $food,
      $quantity;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */


    public function setUp(){
      $this->food=('Flour');
      $this->quantity=(2);
      $this->ingredient = new Ingredient($this->food, $this->quantity);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->ingredient);
    }

    public function testCreateIngredient(){
      $this->assertInstanceOf(Ingredient::class, $this->ingredient, 'Object must be instance of Ingredient.');
    }

    public function testConstructFood() {
      $this->assertEquals($this->food, $this->ingredient->getFood(), 'Food wasn\'t set up properly in constructor.');
    }

    public function testConstructQuantity() {
      $this->assertEquals($this->quantity, $this->ingredient->getQuantity(), 'Quantity wasn\'t set up properly in constructor.');
    }

    public function testSetFood(){
      $fi = ('Broccoli');
      $this->ingredient->setFood($fi);
    	$this->assertEquals($fi, $this->ingredient->getFood(), '');
    }

    public function testSetQuantity(){
      $q = (5);
      $this->ingredient->setQuantity($q);
    	$this->assertEquals($q, $this->ingredient->getQuantity(), '');
    }

}

?>
