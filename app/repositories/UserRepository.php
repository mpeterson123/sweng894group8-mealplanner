<?php
namespace Base\Repositories;

require_once __DIR__.'/../repositories/Repository.php';


use Base\Repositories\Repository;


class UserRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }
    public function checkUser($uname,$pwd){
      $query = $this->db->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
			$query->bind_param("ss",$uname,$pwd);
      $query->execute();
      $result = $query->get_result();
      $row = $result->fetch_assoc();
      return $row;
	  }

    public function find($username){
        $query = $this->db->prepare('SELECT * FROM users WHERE username = ?');
        $query->bind_param("s",$username);
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
        return $this->db->query('SELECT * FROM users')->fetch_all();
    }
    // Not Implemented yet
    public function remove($object){
        $query = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $query->bind_param(array(
            'id' => $food->id
        ));
    }

    protected function insert($object){
        $query = $this->db
            ->prepare('INSERT INTO food
                (name, unitcost, userid)
                VALUES(?,?,?)');
        $query->bind_param(array(
            'name' => $food->name,
            'name' => $food->unitCost,
            'name' => $food->user_id,
        ));
        $query->execute();
    }

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
