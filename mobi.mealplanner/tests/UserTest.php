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
      $this->user = new User();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->user);
    }

	public function testUpdateEmailAddress(){
		$this->user->updateEmail('test@domain.com');
		$this->assertEquals($this->user->getEmail(),'test@domain.com', 'Email must match supplied value');
	}

	public function testRegisterUser(){
		$this->user->register('John','Smith','test@domain.com','jsmith','password123');
		$this->assertEquals($this->user->getFirstName(),'John', 'Name must be John');
		$this->assertEquals($this->user->getEmail(),'test@domain.com', 'Email must be test@domain.com');
	}

	public function testLogin(){
		$this->user->login('jsmith','password123');
		$this->assertTrue($this->user->isLoggedIn(), 'User must be logged in');
	}

	public function testLogout(){
		$this->user->logout();
		$this->assertFalse($this->user->isLoggedIn(), 'User must not be logged in.');
	}

	public function testDeleteUser(){
		$this->user->delete();
		$this->assertNull($this->user->getFirstName(), 'User cannot exist.');
	}

	public function testUpdatePassword(){
		$newPassword = 'mynewpass';
		$this->user->updatePassword($newPassword);
		$this->assertEquals($newPassword, $this->user->getPassword(), 'New password must match supplied password');
	}

	public function testResetPassword(){
		$password = $this->user->getPassword();
		$this->user->resetPassword();
		$this->assertNotEquals($password, $this->user->getPassword(), 'Current password must not match previous password');
	}

}
