<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;


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
        $row = $result->fetch_assoc();
        return $row;
    }



    public function save($user){
        if(isset($this->id) && $this->find($user->id))
        {
            $this->update($user);
        }
        else {
            $this->insert($user);
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

    public function insert($object){
        $today = date('Y-m-d');
        // Insert into household
        $query = $this->db->prepare('INSERT INTO household
                (name)
                VALUES(?)');
        $query->bind_param("s",$object['name']);
        $query->execute();

        // Get householdId
        $query = $this->db->prepare('SELECT * FROM household WHERE name = ? order by id DESC');
        $query->bind_param("s",$object['name']);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();
        $hhId = $row['id'];

        // Insert into usersHouseholds
        $query = $this->db->prepare('INSERT INTO usersHouseholds
                (userId,householdId)
                VALUES(?,?)');
        $query->bind_param("ss",$object['userId'],$hhId);
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
