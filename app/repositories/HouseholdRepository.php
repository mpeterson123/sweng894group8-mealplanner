<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;

// File-specific classes
use Base\Factories\HouseholdFactory;
use Base\Factories\UserFactory;

/**
 * SQL command wrapper for households
 */
class HouseholdRepository extends Repository implements EditableModelRepository {
    private $db,
        $householdFactory;

    public function __construct($db, $householdFactory){
        $this->db = $db;
        $this->householdFactory = $householdFactory;
    }

    public function find($id){
        $query = $this->db->prepare('SELECT * FROM household WHERE id = ?');
        $query->bind_param("s",$id);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $householdRow = $result->fetch_assoc();
        $household = $this->householdFactory->make($householdRow);

        return $household;
    }

    public function allForUser($user){
        $query = $this->db->prepare('SELECT household.* FROM household JOIN usersHouseholds ON usersHouseholds.householdId = household.id AND usersHouseholds.userId = ?');
        @$query->bind_param("i",$user->getId());
        $query->execute();
        $result = $query->get_result();

        $households = array();
        while($householdRow = $result->fetch_assoc()){
            $households[] = $this->householdFactory->make($householdRow);
        }
        return $households;
    }

    public function save($household){
        $success = false;
        if($household->getId() && $this->find($household->getId()))
        {
            $success = $this->update($household);
        }
        else {
            $success = $this->insert($household);
        }
        return $success;
    }

    public function all(){
        return $this->db->query('SELECT * FROM household')->fetch_all();
    }

    public function remove($id){
        $query = $this->db->prepare('DELETE FROM household WHERE id = ?');
        $query->bind_param("i",$id);

        return $query->execute();
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
        if($this->connect($user->getId(), $this->db->insert_id)){
            return true;
        }
        return false;
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

    /**
     * Remove a user to a household
     * @param  integer $userId Id of user to connect
     * @param  integer $hhId   Id of household to connect
     * @return bool    Whether query was successful
     */
    public function disconnect($userId,$hhId){
        $query = $this->db->prepare('DELETE FROM usersHouseholds WHERE userId=? AND householdId=?');
        $query->bind_param(
            "ii",
            $userId,
            $hhId
        );
        if (!$query->execute()){
            return false;
        }

        $currHouseholdQuery = $this->db->prepare('UPDATE users SET currHouseholdId = NULL WHERE id = ?');
        $currHouseholdQuery->bind_param("i", $userId);

        if (!$currHouseholdQuery->execute()){
            return false;
        }
    }
    /**
     * Update household
     * @param  Household $hh    Household object to update in DB
     */
    public function update($hh){
        $query = $this->db
            ->prepare('UPDATE household SET
                name = ?,
                owner = ?
                WHERE id = ?');

        @$query->bind_param(
             'ssi',
             $hh->getName(),
             $hh->getOwner(),
             $hh->getId()
        );

        return $query->execute();
    }
}
