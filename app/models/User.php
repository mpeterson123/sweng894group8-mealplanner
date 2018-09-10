<?php
namespace Base\Models;

class User{
	private $username,
		$password;


	public function __construct(){
    }

	///////////////
	// Username //
	///////////////

    public function setUsername($username){
		$username = trim($username);

        if($username == ''){
            throw new \Exception(
                "Username cannot be empty", 1);
        }

        if(strlen($username) > 20){
            throw new \Exception(
                "Username cannot be longer than 20 characters", 1);
        }

		if(!preg_match('/^[a-z0-9]+$/i', $username))
		{
			throw new \Exception(
				"First Name cannot be longer than 20 characters", 1);
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
            throw new \Exception(
                "First Name cannot be empty", 1);
        }

        if(strlen($firstName) > 20){
            throw new \Exception(
                "First Name cannot be longer than 20 characters", 1);
        }

        $this->firstName = trim($firstName);
    }

    public function getFirstName(){
        return $this->firstName;
    }

	///////////////
	// Password //
	///////////////

	public function setPassword($password){
        if($password == ''){
            throw new \Exception(
                "Password cannot be empty", 1);
        }

        if(strlen($password) < 8){
            throw new \Exception(
                "Password cannot be shorter than 8 characters", 1);
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

}
?>
