<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;

// File-specific classes
use Base\Factories\UserFactory;

class UserRepository extends Repository implements EditableModelRepository {
    private $db,
    $userFactory;

    public function __construct($db, $userFactory){
        $this->db = $db;
        $this->userFactory = $userFactory;
    }


    public function checkUser($username, $password){
        $query = $this->db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
		$query->bind_param("ss",$username, $password);
        $query->execute();
        $result = $query->get_result();
        $userRow = $result->fetch_assoc();

        if(!$userRow){
            return NULL;
        }

        $user = $this->userFactory->make($userRow);
        return $user;
	}

    public function find($username){
        $query = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $query->bind_param("s",$username);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $userRow = $result->fetch_assoc();
        $user = $this->userFactory->make($userRow);

        return $user;
    }


    public function get($field,$value){
      $query = $this->db->prepare('SELECT * FROM users WHERE '.$field.' = ?');
      $query->bind_param("s",$value);
      $query->execute();
      $result = $query->get_result();
      $row = $result->fetch_assoc();
      return $row;
    }

    public function confirmEmail($email){
      $query = $this->db->prepare('UPDATE users SET activated = 1 WHERE email = ?');
			$query->bind_param("s",$email);
      $query->execute();
    }

    public function setPassTemp($email,$pass){
      $query = $this->db->prepare('UPDATE users SET passTemp = ? WHERE email = ?');
			$query->bind_param("ss",$pass,$email);
      $query->execute();
    }
    public function setValue($vField,$value,$idField,$id){
      $query = $this->db->prepare('UPDATE users SET '.$vField.' = ? WHERE '.$idField.' = ?');
			$query->bind_param("ss",$value,$id);
      $query->execute();
    }

    public function save($user){
        $success = false;
        if($user->getId() && $this->get('id',$user->getId()))
        {
            $success = $this->update($user);
        }
        else {
            $success = $this->insert($user);
        }
        return $success;
    }
    public function all(){
        return $this->db->query('SELECT * FROM users')->fetch_all();
    }

    public function allForHousehold($household){
        $query = $this->db->prepare('SELECT users.* FROM users JOIN usersHouseholds ON usersHouseholds.userId = users.id WHERE usersHouseholds.householdId = ?');
        @$query->bind_param("i",$household->getId());
        $query->execute();
        $result = $query->get_result();

        $users = array();
        while($userRow = $result->fetch_assoc()){
            $users[] = $this->userFactory->make($userRow);
        }
        return $users;
    }

    public function getHouseholds($user){
        $householdIds = array();
        $query = $this->db->prepare('SELECT * FROM usersHouseholds WHERE userId = ?');
        $query->bind_param("s",$user->getId());
        $query->execute();
        $result = $query->get_result();
        while($row = $result->fetch_assoc()){
          $householdIds[] = $row['householdId'];
        }

        $households = array();
        foreach($householdIds as $householdId){
          $query = $this->db->prepare('SELECT * FROM household WHERE id = ?');
          $query->bind_param("s",$householdId);
          $query->execute();
          $result = $query->get_result();
          while($row = $result->fetch_assoc()){
            $households[$householdId] = $row['name'];
          }
        }
        return $households;
    }

    public function remove($user){
        $query = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $query->bind_param("i",$user->getId());
        $query->execute();
    }

    public function insert($user){
        $today = date('Y-m-d');
        $query = $this->db->prepare('INSERT INTO users
                (username, password, email, joined, namefirst, namelast)
                VALUES(?,?,?,?,?,?)');
        $query->bind_param("ssssss",
            $user->getUsername(),
            $user->getPassword(),
            $user->getEmail(),
            $today,
            $user->getFirstName(),
            $user->getLastName()
        );
        return $query->execute();
    }

    public function update($user){
        $query = $this->db
            ->prepare('UPDATE users SET
                password = ?,
                email = ?,
                namefirst = ?,
                namelast = ?
                WHERE id = ?
            ');

        @$query->bind_param(
            'ssssi',
            $user->getPassword(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getId()
        );
        return $query->execute();
    }
    public function selectHousehold($user,$hhId){
      $query = $this->db->prepare('UPDATE users set currHouseholdId=? where id=?');
      $query->bind_param(
          "ii",
          $hhId,
          $user->getId()
        );
      $query->execute();
    }
    public function setProfilePicture($user,$filename){
      $query = $this->db->prepare('UPDATE users SET profilePic = ? WHERE id = ?');
			$query->bind_param("si",$filename,$user->getId());
      $query->execute();
    }
}
