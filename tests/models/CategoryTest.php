<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\Category;


class CategoryTest extends TestCase {
    // Variables to be reused
    private $category;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
        $this->category = new Category();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->category);
    }

    public function testCreateCategory(){
    	$this->assertInstanceOf(
            'Base\Models\Category',
            new Category(),
            'Object must be instance of Category');
    }

    /////////
    // Id  //
    /////////
    public function testSetAndGetId(){
        $id = 1;
        $this->category->setId($id);
        $this->assertEquals($this->category->getId(), $id);
    }

    public function testIdCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->category->setId(NULL);
    }

    public function testIdIsAnInteger(){
        $intId = 123;
        $this->category->setId($intId);
        $this->assertInternalType('integer', $this->category->getId());
    }

    public function testNonIntIdsAreRejected(){
        $nonIntId = '123';
        $this->expectException(\Exception::class);
        $this->category->setId($nonIntId);
    }

    //////////
    // Name //
    //////////

    public function testSetName(){
        $name = 'My Category';
        $this->category->setName($name);
        $this->assertEquals($this->category->getName(), $name);
    }

    public function testNameCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->category->setName('');
    }

    public function testNameCannotBeLongerThan20Chars(){
        $longName = '123456789012345678901234567890';
        $this->expectException(\Exception::class);
        $this->category->setName($longName);
    }

    public function testNameCannotHaveExtraWhitespace(){
        $nameWithWhitespace = ' My Category   ';
        $expectedName =  'My Category';
        $this->category->setName($nameWithWhitespace);

        $this->assertEquals($this->category->getName(), $expectedName,
            'Name must be trimmed.');
    }

    // public function testNameIsString(){
    //     $name = 'Category';
    //     $expectedName =  'My Category';
    //     $this->category->setName($name);
    //
    //     $this->assertEquals($this->category->getName(), $expectedName,
    //         'Name must be trimmed.');
    // }
}
