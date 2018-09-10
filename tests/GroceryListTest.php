<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/GroceryList.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\GroceryList;


class GroceryListTest extends TestCase {
    // Variables to be reused
    private $grocerylist;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

        $name = 'Name';
        $this->grocerylist = new GroceryList($name);
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->grocerylist);
    }

    public function testCreateGroceryList(){
        $name = 'Name';
		
    	$this->assertInstanceOf(
            'Base\Models\GroceryList',
            new GroceryList($name),
            'Object must be instance of GroceryList');
    }


    public function testGetName(){
        $this->assertEquals($this->grocerylist->getName(), 'Banana');
    }

}
