<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/GroceryItem.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\GroceryItem;


class GroceryItemTest extends TestCase {
    // Variables to be reused
    private $groceryitem;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

        $name = 'Name';
        $this->groceryitem = new GroceryItem($name);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->groceryitem);
    }

    public function testCreateGroceryItem(){
        $name = 'Name';
		
    	$this->assertInstanceOf(
            'Base\Models\GroceryItem',
            new GroceryItem($name),
            'Object must be instance of GroceryItem');
    }


    public function testGetName(){
        $this->assertEquals($this->groceryitem->getName(), 'Banana');
    }

}
