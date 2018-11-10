<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\User;
use Base\Repositories\HouseholdRepository;

/**
 * Handles User model instantiation
 */
class UserFactory extends Factory {

    private $householdRepository;

    public function __construct($householdRepository){
        $this->householdRepository = $householdRepository;
    }

    /**
     * Creates a new instance of User model
     * @param  array    $userArray A user's properties
     * @return User                A user object
     */
    public function make($userArray):User
    {
        $user = new User();

        // If user exists, assign the id, households, and current household
        if(isset($userArray['id'])){
            $user->setId($userArray['id']);
            $households = $this->householdRepository->allForUser($user);
            $user->setHouseholds($households);

            $currHousehold = $this->householdRepository->find($userArray['currHouseholdId']);
            $user->setCurrHousehold($currHousehold);
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
        $user->setProfilePic($userArray['profilePic']);

        return $user;
    }



}
