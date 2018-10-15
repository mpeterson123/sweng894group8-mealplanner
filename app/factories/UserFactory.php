<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\User;
use Base\Repositories\HouseholdRepository;

/**
 * Handles User model instantiation
 */
class UserFactory {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    /**
     * Creates a new instance of User model
     * @param  array    $userArray A user's properties
     * @return User                A user object
     */
    public function make(array $userArray):User
    {
        $user = new User();
        if(isset($userArray['id'])){
            $user->setId($userArray['id']);
            $households = (new HouseholdRepository($this->db))->allForUser($userArray);
            $user->setHouseholds($households);
        }
        else{
            $user->setHouseholds(array());
        }
        $user->setUsername($userArray['username']);
        $user->setPassword($userArray['password']);
        $user->setEmail($userArray['email']);
        $user->setJoined($userArray['joined']);
        $user->setFirstName($userArray['namefirst']);
        $user->setLastName($userArray['namelast']);
        $user->setActivated($userArray['activated']);
        $user->setPassTemp($userArray['passTemp']);

        return $user;
    }



}
