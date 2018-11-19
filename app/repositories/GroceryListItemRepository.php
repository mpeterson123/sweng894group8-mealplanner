<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;


// File-specific classes
use Base\Factories\GroceryListItemFactory;
use Base\Models\FoodItem;

/**
 * SQL command wrapper for grocery list items
 */
class GroceryListItemRepository extends Repository implements EditableModelRepository {
    private $db,
        $groceryListItemFactory;

    public function __construct($db, $groceryListItemFactory){
        $this->db = $db;
        $this->groceryListItemFactory = $groceryListItemFactory;
    }

    /**
     * Find a single grocery list item by id
     * @param  integer $id items's id
     * @return array       associative array of item's details
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM groceryListItems WHERE id = ?');
        $query->bind_param("s", $id);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $groceryListItemRow = $result->fetch_assoc();
        $groceryListItem = $this->groceryListItemFactory->make($groceryListItemRow);

        return $groceryListItem;
    }

    /**
     * Find a single grocery list item by it's food item's id
     * @param  integer $id Food items's id
     * @return array       associative array of item's details
     */
    public function findByFoodId($id){
        $query = $this->db->prepare('SELECT * FROM groceryListItems WHERE foodItemId = ?');
        $query->bind_param("i", $id);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $groceryListItemRow = $result->fetch_assoc();
        $groceryListItem = $this->groceryListItemFactory->make($groceryListItemRow);

        return $groceryListItem;
    }

    /**
     * Inserts or updates an item in the database
     * @param  Base\Models\GroceryListItem $groceryListItem item to be saved
     * @return void
     */
    public function save($groceryListItem){

        $success = false;
        if($groceryListItem->getId() && $this->find($groceryListItem->getId()))
        {
            $success = $this->update($groceryListItem);
        }
        else {
            $success = $this->insert($groceryListItem);
        }

        return $success;
    }

    /**
     * Get all grocery list items added by a user
     * @return array Associative array of grocery list items
     */
    public function all(){
        return $this->db->query('SELECT * FROM groceryListItems ORDER by name')->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all grocery list items added by a household
     * @param  Household $household [description]
     * @return array Associative array of grocery list items
     */
    public function allForHousehold($household){
        $query = $this->db->prepare('SELECT groceryListItems.* FROM groceryListItems JOIN foods ON foods.id = groceryListItems.foodItemId AND foods.householdId = ? ORDER by name');
        @$query->bind_param("s", $household->getId());
        $query->execute();

        $result = $query->get_result();
        $groceryListItemRows = $result->fetch_all(MYSQLI_ASSOC);

        $collection = array();

        foreach($groceryListItemRows as $groceryListItemRow){
            $collection[] = $this->groceryListItemFactory->make($groceryListItemRow);
        }

        return $collection;
    }

    /**
     * Delete an item from the database
     * @param  integer $id  item's id
     * @return bool         Whether query was successful
     */
    public function remove($id){
        $query = $this->db->prepare('DELETE FROM groceryListItems WHERE id = ?');
        $query->bind_param("s", $id);
        return $query->execute();
    }

    /**
     * Insert item into the database
     * @param  Base\Models\GroceryListItem $groceryListItem   Item to be stored
     * @return bool                         Whether query was successful
     */
    public function insert($groceryListItem){
        $query = $this->db
            ->prepare('INSERT INTO groceryListItems
                (foodItemId, amount)
                VALUES (?, ?)
            ');


        // @ operator to suppress bind_param asking for variables by reference
        // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
        @$query->bind_param("is",
            $groceryListItem->getFoodItem()->getId(),
            $groceryListItem->getAmount()
        );
        return $query->execute();
    }

    /**
     * Update grocery list item in database
     * @param  Base\Models\GroceryListItem $groceryListItem Item to be updated
     * @return bool                 Whether query was successful
     */
    public function update($groceryListItem){
        $query = $this->db
            ->prepare('UPDATE groceryListItems
                SET amount = ?
                WHERE id = ?
            ');

        // @ operator to suppress bind_param asking for variables by reference
        // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
        @$query->bind_param("si",
            $groceryListItem->getAmount(),
            $groceryListItem->getId()
        );
        return $query->execute();

    }

    /**
     * Check if grocery list items belongs to a household
     * @param  integer $groceryListItemItemId          Food item's id
     * @param  Household $household     Current user
     * @return bool                     Whether food belongs to user
     */
    public function groceryListItemBelongsToHousehold($groceryListItemItemId, $household)
    {
        $query = $this->db->prepare('SELECT gli.id FROM groceryListItems as gli
            JOIN foods as f
            ON f.id = gli.foodItemId
            AND gli.id = ? AND f.householdId = ?
            GROUP BY f.householdId');
        @$query->bind_param("ii", $groceryListItemItemId, $household->getId());
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

    public function qtyForGroceryList(FoodItem $foodItem){
        $query = $this->db->prepare('SELECT qtyForGroceryList FROM VIEW_calculationsForGroceryLists WHERE foodId = ? LIMIT 1');
        @$query->bind_param("i", $foodItem->getId());
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows == 0){
            return 0;
        }

        $result = $result->fetch_assoc();
        return $result['qtyForGroceryList'];
    }

}
