<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;

// File-specific classes
use Base\Factories\HouseholdFactory;
use Base\Factories\UserFactory;

class HouseholdRepository extends Repository {
    private $db,
        $householdFactory,
        $userFactory;

    public function __construct($db){
        $this->db = $db;

        // TODO Use dependeny injection
        $this->householdFactory = new HouseholdFactory();
        $this->userFactory = new UserFactory($db);
    }


    public function find($id){
        $query = $this->db->prepare('SELECT * FROM household WHERE id = ?');
        $query->bind_param("s",$id);
        $query->execute();
        $result = $query->get_result();
        $householdRow = $result->fetch_assoc();
        $household = $this->householdFactory->make($householdRow);

        return $household;
    }

    public function allForUser($user){
        $query = $this->db->prepare('SELECT household.* FROM household JOIN usersHouseholds ON usersHouseholds.householdId = household.id WHERE usersHouseholds.userId = ?');
        @$query->bind_param("i",$user->getId());
        $query->execute();
        $result = $query->get_result();

        $households = array();
        while($householdRow = $result->fetch_assoc()){
            $households[] = $this->householdFactory->make($householdRow);
        }
        return $households;
    }
    public function allForHousehold($hh){
        $query = $this->db->prepare('SELECT users.* FROM users JOIN usersHouseholds ON usersHouseholds.userId = users.id WHERE usersHouseholds.householdId = ?');
        @$query->bind_param("i",$hh->getId());
        $query->execute();
        $result = $query->get_result();

        $users = array();
        while($userRow = $result->fetch_assoc()){
            $users[] = $this->userFactory->make($userRow);
        }
        return $users;
    }


    public function save($household){
        if(isset($this->id) && $this->find($household->id))
        {
            $this->update($household);
        }
        else {
            return $this->insert($household);
        }
    }
    public function all(){
        return $this->db->query('SELECT * FROM household')->fetch_all();
    }

    public function remove($id){
        $query = $this->db->prepare('DELETE FROM household WHERE id = ?');
        $query->bind_param("s",$id);
        $query->execute();
    }

    public function insert($household){
        // Insert into household
        $newHouseholdQuery = $this->db->prepare('INSERT INTO household
                (name,owner)
                VALUES(?,?)');
        @$newHouseholdQuery->bind_param("ss",$household->getName(),$household->getOwner());
        $newHouseholdQuery->execute();

        // Assign to user
        $user = (new Session())->get('user');
        $this->connect($user->getId(), $this->db->insert_id);
    }

    /**
     * Connect a user to a household
     * @param  integer $userId Id of user to connect
     * @param  integer $hhId   Id of household to connect
     */
    public function connect($userId,$hhId):void{
      $query = $this->db->prepare('INSERT INTO usersHouseholds
              (userId,householdId)
              VALUES(?,?)');
      $query->bind_param(
          "ii",
          $userId,
          $hhId);
      $query->execute();
    }

    // Not Implemented yet
    protected function update($object){
        $query = $this->db
            ->prepare('UPDATE food
                SET name = ?, unitcost =?)
                VALUES(?,?)');
        $query->bind_param(array(
            'name' => $food->name,
            'name' => $food->unitCost,
        ));
        $query->execute();
    }
}
