<?php
namespace Base\Repositories;

require_once __DIR__.'/../repositories/Repository.php';


use Base\Repositories\Repository;


class FoodItemRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function find($id){

        $query = $this->db->prepare('SELECT * FROM foods WHERE id = ?');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc();
    }

    public function save($foodItem){

        if($foodItem->getId() && $this->find($foodItem->getId()))
        {
            $this->update($foodItem);
        }
        else {
            die("TODO insert");
            $this->insert($foodItem);
        }
    }

    /**
     * Get all food items added by a user
     * @return array Associative array of food items
     */
    public function all(){
        return $this->db->query('SELECT * FROM foods ORDER by name')->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all food items added by a user
     * @return array Associative array of food items
     */
    public function allForUser($userId){
        $query = $this->db->prepare('SELECT * FROM VIEW_foods WHERE user_id = ? ORDER by name');
        $query->bind_param("s", $userId);
        $query->execute();

        $result = $query->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function remove($id){
        $query = $this->db->prepare('DELETE FROM foods WHERE id = ?');
        $query->bind_param("s", $id);
        return $query->execute();
    }

    protected function insert($food){
        $query = $this->db
            ->prepare('INSERT INTO foods
                (name, unit_cost, user_id)
                VALUES(?,?,?)');
        $query->bind_param(array(
            'name' => $food->name,
            'name' => $food->unitCost,
            'name' => $food->user_id,
        ));
        $query->execute();
    }

    protected function update($food){
        $query = $this->db
            ->prepare('UPDATE foods
                SET
                    name = ?,
                    stock = ?,
                    unit_id = ?,
                    category_id = ?,
                    units_in_container = ?,
                    container_cost = ?,
                    unit_cost = ?
                WHERE id = ?
            ');

        // @ operator to suppress bind_param asking for variables by reference
        // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
        @$query->bind_param("sdiidddi",
            $food->getName(),
            $food->getstock(),
            $food->getUnit()->getId(),
            $food->getCategory()->getId(),
            $food->getUnitsInContainer(),
            $food->getContainerCost(),
            $food->getUnitCost(),
            $food->getId()
        );
        $query->execute();

    }

    public function foodBelongsToUser($foodId, $userId)
    {
        $query = $this->db->prepare('SELECT * FROM foods WHERE id = ? AND user_id = ?');
        $query->bind_param("si", $foodId, $userId);
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

}
