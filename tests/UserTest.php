<?php
namespace Base\Test;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';
require_once dirname(dirname(__FILE__)).'/app/models/User.php';


use PHPUnit\Framework\TestCase;
// Add the classes you are testing
use Base\Models\User;


class UserTest extends TestCase {
	// Variables to be reused
	private $user;

    /**
     * Create instances or whatever you need to reuse in several tests here
     */
    public function setUp(){
		$username = 'janedoe';
		$this->user = new User($username);
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
		$this->assertEquals($this->user->getFirstUsername(),'John', 'Username must be John');
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
		$this->assertNull($this->user->getFirstUsername(), 'User cannot exist.');
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

	//////////////
	// Actions //
	//////////////

	public function testCreateUser(){
        $username = 'newuser';

    	$this->assertInstanceOf(
            'Base\Models\User',
            new User($username),
            'Object must be an instance of User');
    }

    //////////
    // Username //
    //////////

    public function testGetUsername(){
        $this->assertEquals($this->user->getUsername(), 'janedoe');
    }

    public function testSetUsername(){
        $this->user->setUsername('anotherusername');
        $this->assertEquals($this->user->getUsername(), 'anotherusername');
    }

    public function testUsernameCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->user->setUsername('');
    }

    public function testUsernameCannotBeLongerThan20Chars(){
        $longUsername = 'ThisIsAVeryLongUsername';
        $this->expectException(\Exception::class);
        $this->user->setUsername($longUsername);
    }

    public function testUsernameCannotHaveExtraWhitespace(){
        $userNameWithWhitespace = '       aUsername   ';
        $expectedUsername =  'aUsername';
        $this->user->setUsername($userNameWithWhitespace);

        $this->assertEquals($this->user->getUsername(), $expectedUsername,
            'Username must be trimmed.');
    }

	public function testInvalidUsernameIsRejected(){
		$invalidUsername = 'A *bad*_username!';
		$this->expectException(\Exception::class);
        $this->user->setUsername($invalidUsername);
	}

	public function testUsernameIsAlphanumeric(){
		$alphanumUsername = 'Agoodusername';
		$this->assertRegExp('/[a-z0-9]+/i', $this->user->setUsername($alphanumUsername));
	}



    public function testUsernameMustBeUnique(){

    }

}
