<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;

// File-specific classes
use Base\Factories\HouseholdFactory;

class HouseholdRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }


    public function find($id){
        $query = $this->db->prepare('SELECT * FROM household WHERE id = ?');
        $query->bind_param("s",$id);
        $query->execute();
        $result = $query->get_result();
        $householdRow = $result->fetch_assoc();
        $household = (new HouseholdFactory($this->db))->make($householdRow);

        return $household;
    }

    public function allForUser($userId){
        $query = $this->db->prepare('SELECT household.* FROM household JOIN usersHouseholds ON usersHouseholds.householdId = household.id AND usersHouseholds.userId = ?');
        $query->bind_param("i",$userId);
        $query->execute();
        $result = $query->get_result();

        $households = array();
        $householdFactory = new HouseholdFactory($this->db);
        while($householdRow = $result->fetch_assoc()){
            $households[] = $householdFactory->make($householdRow);
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
        @$query->bind_param("ss",(new Session())->get('id'),$this->db->insert_id);
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
