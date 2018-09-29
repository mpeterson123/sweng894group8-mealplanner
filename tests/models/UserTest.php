<?php
namespace Base\Test;

require_once __DIR__.'../../vendor/autoload.php';

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
		$this->user = new User();
    }

    /**
     * Unset any variables you've created
     */
    public function tearDown(){
      unset($this->user);
    }

	///////////////////
	// Instatiation //
	//////////////////

	public function testCreateUser(){
    	$this->assertInstanceOf(
            'Base\Models\User',
            new User(),
            'Object must be an instance of User');
    }

    ///////////////
    // Username //
    //////////////

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

	public function testNonAlphanumericUsernameIsRejected(){
		$invalidUsername = 'A *bad*_username!';
		$this->expectException(\Exception::class);
        $this->user->setUsername($invalidUsername);
	}

    public function testUsernameMustBeUnique(){

    }

	///////////
	// Name //
	///////////

    public function testSetFirstName(){
        $this->user->setFirstName('Samwise');
        $this->assertEquals($this->user->getFirstName(), 'Samwise');
    }

    public function testFirstNameCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->user->setFirstName('');
    }

    public function testFirstNameCannotBeLongerThan65Chars(){
        $longFirstName =
			'ThisIsAVeryLongFirstNameWithLoooooooooooooooooooooootsOfCharacters';
        $this->expectException(\Exception::class);
        $this->user->setFirstName($longFirstName);
    }

    public function testFirstNameCannotHaveExtraWhitespace(){
        $userNameWithWhitespace = '       Aragorn   ';
        $expectedFirstName =  'Aragorn';
        $this->user->setFirstName($userNameWithWhitespace);

        $this->assertEquals($this->user->getFirstName(), $expectedFirstName,
            'FirstName must be trimmed.');
    }

	public function testRejectFirstNameWithInvalidCharacters(){
		$invalidFirstName = 'A name with spaces and *symbols*';
		$this->expectException(\Exception::class);
        $this->user->setFirstName($invalidFirstName);
	}

	///////////////
	// Password //
	///////////////

    public function testSetPassword(){
		$password = 'P4$$w0rd';
        $this->user->setPassword($password);
        $this->assertEquals($this->user->getPassword(), $password);
    }

    public function testPasswordCannotBeEmpty(){
        $this->expectException(\Exception::class);
        $this->user->setPassword('');
    }

    public function testPasswordCannotBeShorterThan8Chars(){
        $shortPassword = '1234567';
        $this->expectException(\Exception::class);
        $this->user->setPassword($shortPassword);
    }

	// public function testResetPassword(){
	// 	$password = $this->user->setPassword('Mypassword');
	// 	$this->user->resetPassword();
	// 	$this->assertNotEquals($password, $this->user->getPassword(), 'Current password must not match previous password');
	// }
	//
	//
	////////////
	// Email //
	////////////

	public function testSetEmail(){
		$email = 'example@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($this->user->getEmail(), $email);
    }

	// public function testRejectInvalidEmail(){
	// 	$email = '%^yug3yir7!g';
	// 	$this->expectException(\Exception::class);
    //     $this->user->setEmail($email);
    // }

	////////////////////////////////////////////////////////////////////////////
	// Actions
	////////////////////////////////////////////////////////////////////////////

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
}
