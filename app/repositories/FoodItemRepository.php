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
        if(isset($this->id) && $this->find($foodItem->id))
        {
            $this->update($foodItem);
        }
        else {
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

    protected function insert($object){
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

    protected function update($object){
        $query = $this->db
            ->prepare('UPDATE foods
                SET name = ?, unit_cost =?)
                VALUES(?,?)');
        $query->bind_param(array(
            'name' => $food->name,
            'name' => $food->unitCost,
        ));
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
