<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;


// File-specific classes
use Base\Factories\GroceryListItemFactory;


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
        $query->execute();
        $result = $query->get_result();
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

        if($groceryListItem->getId() && $this->find($groceryListItem->getId()))
        {
            $this->update($groceryListItem);
        }
        else {
            $this->insert($groceryListItem);
        }
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
        $query = $this->db->prepare('SELECT * FROM groceryListItems JOIN foods ON foods.id = groceryListItems.foodItemId AND foods.householdId = ? ORDER by name');
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
     * @param  Base\Models\GroceryListItem $food   Item to be stored
     * @return bool                         Whether query was successful
     */
    public function insert($food){
        $query = $this->db
            ->prepare('INSERT INTO groceryListItems
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
     * Update grocery list item in database
     * @param  Base\Models\GroceryListItem $food Item to be updated
     * @return bool                 Whether query was successful
     */
    public function update($food){
        $query = $this->db
            ->prepare('UPDATE groceryListItems
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
     * Check if grocery list items belongs to a household
     * @param  integer $foodItemId          Food item's id
     * @param  Household $household     Current user
     * @return bool                     Whether food belongs to user
     */
    public function foodBelongsToHousehold($foodItemId, $household)
    {
        $query = $this->db->prepare('SELECT * FROM groceryListItems WHERE id = ? AND householdId = ?');
        @$query->bind_param("ii", $foodItemId, $household->getId());
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

}
