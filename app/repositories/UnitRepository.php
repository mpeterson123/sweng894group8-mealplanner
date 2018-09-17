<?php
namespace Base\Repositories;

require_once __DIR__.'/../repositories/Repository.php';


use Base\Repositories\Repository;


class UnitRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    /**
     * Search for a unit using its id
     * @param  string $id   the unit's id
     * @return array        unit's details
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM units WHERE id = ? ORDER by name');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Search for a unit using its abbreviation
     * @param  string $abbreviation the unit's abbreviation
     * @return array               unit's details
     */
    public function findByAbbreviation($abbreviation){

        $query = $this->db->prepare('SELECT * FROM units WHERE abbreviation = ? ORDER by name');
        $query->bind_param("s", $abbreviation);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Get all food items added by a user
     * @return array Associative array of food items
     */
    public function all(){
        return $this->db->query('SELECT * FROM units ORDER by name')->fetch_all(MYSQLI_ASSOC);
    }


    public function save($object){}
    public function remove($object){}
    protected function insert($object){}
    protected function update($object){}
}
