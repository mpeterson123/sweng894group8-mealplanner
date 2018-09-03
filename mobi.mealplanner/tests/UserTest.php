<?php
namespace App\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use App\User;


class UserTest extends TestCase {
    // Variables to be reused
	private $user;


    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
      $this->User = new User();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->User);
    }

	public function testUpdateEmailAddress(){
		$user->updateEmail('test@domain.com');
		$this->assertEquals($user->getEmail(),'test@domain.com');
	}

	public function testRegisterUser(){
		$user->register('John','Smith','test@domain.com','jsmith','password123');
		$this->assertEquals($user->getFirstName(),'John');
		$this->assertEquals($user->getEmail(),'test@domain.com');
	}

	public function testLogin(){
		$user->login('jsmith','password123');
		$this->assertTrue($user->isLoggedIn());
	}

	public function testLogout(){
		$user->logout();
		$this->assertFalse($user->isLoggedIn());
	}

	public function testDeleteUser(){
		$user->delete();
		$this->assertNull($user->getFirstName());
	}

}
