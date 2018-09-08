<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
// Example: use Base\NameOfTheClassYouAreTesting;


class NameOfTheClassYouAreTestingTest extends TestCase {
    // Variables to be reused
    // Example: private $classYouAreTesting


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      // Example: $this->classYouAreTesting = new NameOfTheClassYouAreTesting();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      // Example: unset $this->classYouAreTesting;
    }

    /**
     * Failing sample test method
     */
    public function testFailingExample(){
      $falseVar = false;
      $this->assertTrue($falseVar, 'Variable should return true.');
    }

    /**
     * Passing sample test method
     */
    public function testPassingExample(){
      $trueVar = true;
      $this->assertTrue($trueVar, 'Variable should return true.');
    }
}
