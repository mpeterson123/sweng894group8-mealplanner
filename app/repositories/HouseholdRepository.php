<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;

// File-specific classes
use Base\Factories\HouseholdFactory;

class HouseholdRepository extends Repository {
    private $db,
        $householdFactory;

    public function __construct($db){
        $this->db = $db;

        // TODO Use dependeny injection
        $this->householdFactory = new HouseholdFactory();
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
        $query->bind_param("i",$user->getId());
        $query->execute();
        $result = $query->get_result();

        $households = array();
        while($householdRow = $result->fetch_assoc()){
            $households[] = $this->householdFactory->make($householdRow);
        }
        return $households;
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
        $query = $this->db->prepare('INSERT INTO household
                (name)
                VALUES(?)');
        @$query->bind_param("s",$household->getName());
        $query->execute();

        // // Get householdId
        // $query = $this->db->prepare('SELECT * FROM household WHERE name = ? order by id DESC');
        // $query->bind_param("s",$household->getName());
        // $query->execute();
        // $result = $query->get_result();
        // $row = $result->fetch_assoc();
        // $hhId = $row['id'];

        // Insert into usersHouseholds
        $query = $this->db->prepare('INSERT INTO usersHouseholds
                (userId,householdId)
                VALUES(?,?)');
        @$query->bind_param(
            "ss",
            (new Session())->get('user')->getId(),
            $this->db->insert_id
        );
        $query->execute();
    }

    public function connect($userId,$hhId){
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
