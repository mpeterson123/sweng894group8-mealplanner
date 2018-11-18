<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;
use Base\Factories\IngredientFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\CategoryRepository;

class IngredientRepository extends Repository implements EditableModelRepository {
    private $db,
        $ingredientFactory;

    public function __construct($db, $ingredientFactory){
        $this->db = $db;
        $this->ingredientFactory = $ingredientFactory;
    }

    /**
     * Find a single ingredient by id
     * @param  integer $id the ingredient's id
     * @return object       ingredient object
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM ingredients WHERE id = ?');
        $query->bind_param("s", $id);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $ingredientRow = $result->fetch_assoc();
        $ingredient = $this->ingredientFactory->make($ingredientRow);

        return $ingredient;
    }

    /**
     * Find a single ingredient by food ID for a specific recipe
     * @param  integer $foodId the ID of the food item
     * @param integer $recipeId the ID of the recipe
     * @return object       Ingredient object or null
     */
    public function findIngredientByFoodId($foodId, $recipeId){

        $query = $this->db->prepare('SELECT * FROM ingredients WHERE foodId = ? AND recipeId = ?');
        $query->bind_param("ss", $foodId, $recipeId);
        if(!$query->execute()){
            return NULL;
        }
        $result = $query->get_result();

        if(!$result || !$result->num_rows){
            return NULL;
        }
        $ingredientRow = $result->fetch_assoc();
        $ingredient = $this->ingredientFactory->make($ingredientRow);

        return $ingredient;

    }

    /**
     * Inserts or updates an ingredient in the database
     * @param  Base\Models\Ingredient $ingredient ingredient to be saved
     * @return void
     */
    public function save($ingredient){
        if($ingredient->getId() && $this->find($ingredient->getId()))
        {
          $success =   $this->update($ingredient);
        }
        else {
            $success = $this->insert($ingredient);
        }

        return $success;
    }

    /**
     * Get all ingredients
     * @return array Associative array of ingredients
     */
    public function all(){
        //return $this->db->query('SELECT * FROM recipes ORDER by name')->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all ingredients for a particular recipe
     * @return array Array of ingredient objects
     */
    public function allForRecipe($recipeId){
        $query = $this->db->prepare('SELECT * FROM ingredients WHERE recipeId = ? ORDER by foodId');
        $query->bind_param("s", $recipeId);

        if($query->execute()) {
          $result = $query->get_result();
          $ingredientRows = $result->fetch_all(MYSQLI_ASSOC);

          $collection = array();

          foreach($ingredientRows as $ingredientRow){
              $collection[] = $this->ingredientFactory->make($ingredientRow);
          }

          return $collection;
        }
        else {
          $error = $query->error;
          echo "\n" . __CLASS__ . "::" . __FUNCTION__ . ":" . $error . "\n";
          return null;
        }

      }

        /**
     * Delete an ingredient from the database
     * @param  integer $id  ingredient's id
     * @return bool         Whether query was successful
     */

    public function remove($id){

        $query = $this->db->prepare('DELETE FROM ingredients WHERE id = ?');
        $query->bind_param("i", $id);

        $bool = $query->execute();

        if(!$bool) {
          $query->error;
          echo "\n" . __CLASS__ . "::" . __FUNCTION__ . $error . "\n";
        }

        return $bool;

    }

    /**
     * Insert ingredient into the database
     * @param  Base\Models\Ingredient $ingredient   Ingredient to be stored
     * @return bool                     Whether query was successful
     */
    //protected
    public function insert($ingredient){

        $query = $this->db
            ->prepare('INSERT INTO ingredients
                (foodId, recipeId, quantity, unitId)
                VALUES (?, ?, ?, ?)
            ');

        // @ operator to suppress bind_param asking for variables by reference
        // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
        @$query->bind_param("iidi",
            $ingredient->getFood()->getId(),
            $ingredient->getRecipeId(),
            $ingredient->getQuantity()->getValue(),
            $ingredient->getUnit()->getId()
        );

        $bool = $query->execute();
        if($bool) {
          $ingredient->setId($query->insert_id);
        }
        else {
          $query->error;
          echo "\n" . __CLASS__ . "::" . __FUNCTION__ . ":" . $error . "\n";
        }

        return $bool;

    }

    /**
     * Update ingredient in database
     * @param  Base\Models\Ingredient $ingredient Ingredient to be updated
     * @return bool                 Whether query was successful
     */
    public function update($ingredient){

        $query = $this->db
            ->prepare('UPDATE ingredients
                SET
                    foodId = ?,
                    recipeId = ?,
                    quantity = ?,
                    unitId = ?
                WHERE id = ?
            ');

        // @ operator to suppress bind_param asking for variables by reference
        // See: https://stackoverflow.com/questions/13794976/mysqli-prepared-statement-complains-that-only-variables-should-be-passed-by-ref
        @$query->bind_param("iidii",
            $ingredient->getFood()->getId(),
            $ingredient->getRecipeId(),
            $ingredient->getQuantity()->getValue(),
            $ingredient->getUnit()->getId(),
            $ingredient->getId()
        );

        $bool = $query->execute();

        if(!$bool) {
          $error = $query->error;
          echo "\n" . __CLASS__ ."::" . __FUNCTION__ . ":" . $error . "\n";
        }

        return $bool;
    }
}
