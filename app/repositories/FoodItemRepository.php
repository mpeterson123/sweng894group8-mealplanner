<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;


// File-specific classes
use Base\Factories\FoodItemFactory;


class FoodItemRepository extends Repository {
    private $db,
        $foodItemFactory;

    public function __construct($db){
        $this->db = $db;

        // TODO Use dependecy injection
        $categoryRepository = new CategoryRepository($this->db);
        $unitRepository = new UnitRepository($this->db);
        $this->foodItemFactory = new FoodItemFactory($categoryRepository, $unitRepository);
    }

    /**
     * Find a single food item by id
     * @param  integer $id items's id
     * @return array       associative array of item's details
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM foods WHERE id = ?');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $foodItemRow = $result->fetch_assoc();

        $foodItem = $this->foodItemFactory->make($foodItemRow);
        return $foodItem;
    }

    /**
     * Inserts or updates an item in the database
     * @param  Base\Models\FoodItem $foodItem item to be saved
     * @return void
     */
    public function save($foodItem){

        if($foodItem->getId() && $this->find($foodItem->getId()))
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
     * Get all food items added by a household
     * @param  Household $household [description]
     * @return array Associative array of food items
     */
    public function allForHousehold($household){
        $query = $this->db->prepare('SELECT * FROM foods WHERE householdId = ? ORDER by name');
        @$query->bind_param("s", $household->getId());
        $query->execute();

        $result = $query->get_result();
        $foodItemRows = $result->fetch_all(MYSQLI_ASSOC);

        $collection = array();

        foreach($foodItemRows as $foodItemRow){
            $collection[] = $this->foodItemFactory->make($foodItemRow);
        }

        return $collection;
    }

    /**
     * Delete an item from the database
     * @param  integer $id  item's id
     * @return bool         Whether query was successful
     */
    public function remove($id){
        $query = $this->db->prepare('DELETE FROM foods WHERE id = ?');
        $query->bind_param("s", $id);
        return $query->execute();
    }

    /**
     * Insert item into the database
     * @param  Base\Models\FoodItem $food   Item to be stored
     * @return bool                         Whether query was successful
     */
    protected function insert($food){
        $query = $this->db
            ->prepare('INSERT INTO foods
                (name, stock, unitId, categoryId, unitsInContainer, containerCost, unitCost, householdId)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
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
            (new Session())->get('user')->getHouseholds()[0]->getId()
        );
        return $query->execute();
    }

    /**
     * Update food item in database
     * @param  Base\Models\FoodItem $food Item to be updated
     * @return bool                 Whether query was successful
     */
    protected function update($food){
        $query = $this->db
            ->prepare('UPDATE foods
                SET
                    name = ?,
                    stock = ?,
                    unitId = ?,
                    categoryId = ?,
                    unitsInContainer = ?,
                    containerCost = ?,
                    unitCost = ?
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

    /**
     * Check if food items belongs to a household
     * @param  integer $foodId          Food item's id
     * @param  Household $household     Current user
     * @return bool                     Whether food belongs to user
     */
    public function foodBelongsToHousehold($foodId, $household)
    {
        $query = $this->db->prepare('SELECT * FROM foods WHERE id = ? AND householdId = ?');
        @$query->bind_param("ii", $foodId, $household->getId());
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

}
