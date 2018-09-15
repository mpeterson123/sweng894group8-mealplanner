<?php
namespace Base\Models;

class User{
	private $username,
					$password,
					$firstName,
					$lastName,
					$email,
					$loggedIn;


	public function __construct(){
		$this->loggedIn = false;
  }

		public function setAll($array){
			$this->setUsername($array['username']);
			$this->setFirstName($array['namefirst']);
			$this->setLastName($array['namelast']);
			$this->setEmail($array['email']);
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
		////////////
		public function setEmail($email){
        $this->email = trim($email);
    }
		public function getEmail(){
	  		return $this->email;
	  }
		////////////
		// Login //
		/////////////
		public function login($userArray){
			$this->setAll($userArray);
			$_SESSION['username'] = $this->username;
			$this->loggedIn = true;
		}
		public function isLoggedIn(){
				return $this->loggedIn;
		}
		public function logout(){
			$this->loggedIn = false;
		}
}
?>
