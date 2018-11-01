<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\UnitFactory;
use Base\Repositories\Repository;


class UnitRepository extends Repository {
    private $db,
        $unitFactory;

    public function __construct($db, $unitFactory){
        $this->db = $db;
        $this->unitFactory = $unitFactory;
    }

    /**
     * Search for a unit using its id
     * @param  string $id   the unit's id
     * @return array        unit's details
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM units WHERE id = ? ORDER BY name');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $unitRow = $result->fetch_assoc();

        $unit = $this->unitFactory->make($unitRow);

        return $unit;
    }

    /**
     * Get all food items added by a user
     * @return array Associative array of food items
     */
    public function all(){
        $unitRows = $this->db->query('SELECT * FROM units ORDER by name')->fetch_all(MYSQLI_ASSOC);

        $collection = array();
        foreach($unitRows as $unitRow){
            $collection[] = $this->unitFactory->make($unitRow);
        }
        return $collection;
    }


    public function save($object){}
    public function remove($object){}
    protected function insert($object){}
    protected function update($object){}
}
