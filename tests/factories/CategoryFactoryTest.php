<?php
namespace Base\Test;

require_once __DIR__.'/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing and their dependencies
use Base\Factories\CategoryFactory;
use Base\Models\Category;
use Base\Models\User;
use Base\Repositories\UserRepository;



class CategoryFactoryTest extends TestCase {
    // Variables to be reused
    private $categoryFactory;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){

    	////////////////////////////////////////////////////////////////////////
        // Create instance //
    	////////////////////////////////////////////////////////////////////////
        $this->categoryFactory = new CategoryFactory();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->categoryFactory);
    }

    public function testCreateCategoryFactory(){
    	$this->assertInstanceOf(
            'Base\Factories\CategoryFactory',
            new CategoryFactory(),
            'Object must be instance of CategoryFactory');
    }

    public function testMakeCategoryWithId(){
        $categoryArray = array(
            'id' => 1234,
            'name' => 'Sweets',
        );

        $category = $this->categoryFactory->make($categoryArray);
    	$this->assertInstanceOf(
            'Base\Models\Category',
            $category,
            'Object must be instance of Category');

        $this->assertEquals($category->getId(), $categoryArray['id']);
        $this->assertEquals($category->getName(), $categoryArray['name']);
    }

    public function testMakeCategoryWithoutId(){
        $categoryArray = array(
            'name' => 'Grains',
        );

        $category = $this->categoryFactory->make($categoryArray);
    	$this->assertInstanceOf(
            'Base\Models\Category',
            $category,
            'Object must be instance of Category');

        $this->assertEquals($category->getId(), NULL);
        $this->assertEquals($category->getName(), $categoryArray['name']);
    }
}
