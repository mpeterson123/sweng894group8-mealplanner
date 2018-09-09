<?php
namespace Base\Models;

class User{
	private $username;


	public function __construct($username){
        $this->username = $username;
    }

    public function setUsername($username){
        if($username == ''){
            throw new \Exception(
                "Username cannot be empty", 1);
        }

        if(strlen($username) > 20){
            throw new \Exception(
                "Username cannot be longer than 20 characters", 1);
        }

        $this->username = trim($username);
    }

    public function getUsername(){
        return $this->username;
    }
}
?>
