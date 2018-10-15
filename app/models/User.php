<?php
namespace Base\Models;
require_once __DIR__.'/../../vendor/autoload.php';

class User{
	private $username,
					$password,
					$firstName,
					$lastName,
					$email,
					$loggedIn,
					$households,
					$id;


	public function __construct(){
		$this->loggedIn = false;
 	}

	public function setAll($array){
		$this->setUsername($array['username']);
		$this->setFirstName($array['namefirst']);
		$this->setLastName($array['namelast']);
		$this->setEmail($array['email']);
		$this->setHousehold($array['households']);
		$this->setId($array['id']);
	}

	///////////////
	// Id //
	///////////////
	public function setId($id){
    	$this->id = trim($id);
    }
	public function getId(){
  		return $this->id;
	}

	///////////////
	// Username //
	///////////////
    public function setUsername($username){
		$username = trim($username);

		if($username == ''){
			throw new \Exception("Username cannot be empty", 1);
		}

		if(strlen($username) > 20){
			throw new \Exception("Username cannot be longer than 20 characters", 1);
		}

		if(!preg_match('/^[a-z0-9]+$/i', $username)){
			throw new \Exception("First Name cannot be longer than 20 characters", 1);
		}
	    $this->username = $username;
    }

    public function getUsername(){
        return $this->username;
    }

	////////////////
	// FirstName //
	////////////////
	public function setFirstName($firstName){
    if($firstName == ''){
        throw new \Exception("First Name cannot be empty", 1);
    }

    if(strlen($firstName) > 20){
        throw new \Exception("First Name cannot be longer than 20 characters", 1);
    }

    $this->firstName = trim($firstName);
    }
    public function getFirstName(){
        return $this->firstName;
    }

	////////////////
	// LastName //
	////////////////
	public function setLastName($lastName){
		if($lastName == ''){
				throw new \Exception("Last Name cannot be empty", 1);
		}

		if(strlen($lastName) > 20){
				throw new \Exception("First Name cannot be longer than 20 characters", 1);
		}

		$this->lastName = trim($lastName);
	}

	public function getLastName(){
        return $this->lastName;
    }

	////////////////
	// Name //
	////////////////
	public function getName(){
		return $this->firstName ." ". $this->lastName;
	}

	///////////////
	// Password //
	///////////////
	public function setPassword($password){
	    if($password == ''){
	        throw new \Exception("Password cannot be empty", 1);
	    }

	    if(strlen($password) < 8){
	        throw new \Exception("Password cannot be shorter than 8 characters", 1);
	    }

	    $this->password = trim($password);
    }

    public function getPassword(){
        return $this->password;
    }


	////////////
	// Email //
	///////////
	public function setEmail($email){
    	$this->email = trim($email);
    }
	public function getEmail(){
  		return $this->email;
	}

	////////////
	// Joined //
	///////////
	public function setJoined($joined){
    	$this->joined = trim($joined);
    }
	public function getJoined(){
  		return $this->joined;
	}

	///////////////
	// Activated //
	///////////////
	public function setActivated($activated){
    	$this->activated = trim($activated);
    }
	public function getActivated(){
  		return $this->activated;
	}

	//////////////
	// PassTemp //
	//////////////
	public function setPassTemp($passTemp){
    	$this->passTemp = trim($passTemp);
    }
	public function getPassTemp(){
  		return $this->passTemp;
	}

	///////////////
	// Household //
	///////////////
	public function setHouseholds($households){
    $this->households = $households;
	}

	public function getHouseholds(){
  		return $this->households;
  	}

}
?>
