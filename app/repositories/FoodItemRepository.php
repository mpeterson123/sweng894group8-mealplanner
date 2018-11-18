<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;


// File-specific classes
use Base\Factories\FoodItemFactory;


class FoodItemRepository extends Repository implements EditableModelRepository {
    private $db,
        $foodItemFactory;

    public function __construct($db, $foodItemFactory){
        $this->db = $db;
        $this->foodItemFactory = $foodItemFactory;
    }

    /**
     * Find a single food item by id
     * @param  integer $id items's id
     * @return object       Food item object or null
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM foods WHERE id = ?');
        $query->bind_param("s", $id);

        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $foodItemRow = $result->fetch_assoc();
        $foodItem = $this->foodItemFactory->make($foodItemRow);

        return $foodItem;
    }

    /**
     * Find a single food item by name
     * @param  string $name items's name
     * @return object       FoodItem object
     */
    public function findFoodItemByName($name){

        $query = $this->db->prepare('SELECT * FROM foods WHERE name = ?');
        $query->bind_param("s", $name);

        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $foodItemRow = $result->fetch_assoc();
        $foodItem = $this->foodItemFactory->make($foodItemRow);

        return $foodItem;

    }
    public function findHouseholdFoodItemByName($name,$hhId){

        $query = $this->db->prepare('SELECT * FROM foods WHERE name = ? and householdId = ?');
        $query->bind_param("ss", $name,$hhId);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
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

        $success = false;
        if($foodItem->getId() && $this->find($foodItem->getId()))
        {
            $success = $this->update($foodItem);
        }
        else {
            $success = $this->insert($foodItem);
        }
        return $success;
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
     * Count all food items added by a household
     * @param  Household    $household Household to check
     * @return integer      Total food items for household
     */
    public function countForHousehold($household){
        $query = $this->db->prepare('SELECT * FROM foods WHERE householdId = ? ORDER by name');
        @$query->bind_param("s", $household->getId());
        $query->execute();

        $result = $query->get_result();

        return $result->num_rows;
    }

    /**
     * Get all food items that are not in a household's grocery list
     * @param  Household $household [description]
     * @return array Associative array of food items
     */
    public function itemsAddableToHouseholdGroceryList($household){
        $query = $this->db->prepare('SELECT * FROM foods WHERE householdId = ? AND foods.id NOT IN (SELECT foodItemId FROM groceryListItems) ORDER by name');
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
    public function insert($food){
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
            (new Session())->get('user')->getCurrHousehold()->getId()
        );
        return $query->execute();
    }

    /**
     * Update food item in database
     * @param  Base\Models\FoodItem $food Item to be updated
     * @return bool                 Whether query was successful
     */
    public function update($food){
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

        return $query->execute();

    }

    /**
     * Check if food items belongs to a household
     * @param  integer $foodId          Food item's id
     * @param  Household $household     Current household
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

    /**
     * Check if food item is addable to the household's grocery list
     * @param  integer $foodId          Food item's id
     * @param  Household $household     Current household
     * @return bool                     Whether food item is addable to list
     */
    public function isAddableToHouseholdGroceryList($foodId, $household)
    {
        // TODO Replace this query with view to optimize performance if necessary
        $query = $this->db->prepare('SELECT id FROM foods WHERE foods.id = ?
            AND householdId = ?
            AND foods.id NOT IN (SELECT foodItemId FROM groceryListItems)');
        @$query->bind_param("ii", $foodId, $household->getId());
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }



}
