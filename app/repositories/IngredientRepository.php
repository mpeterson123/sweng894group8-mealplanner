<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;
use Base\Factories\IngredientFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\CategoryFactory;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\CategoryRepository;

class IngredientRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;

        // TODO Use dependecy injection
        $categoryFactory = new CategoryFactory($this->db);
        $categoryRepository = new CategoryRepository($this->db, $categoryFactory);
        $unitRepository = new UnitRepository($this->db);
        $foodItemFactory = new FoodItemFactory($categoryRepository, $unitRepository);
        $foodItemRepository = new FoodItemRepository($this->db, $foodItemFactory);
        $this->ingredientFactory = new IngredientFactory($this->db, $foodItemRepository);
    }

    /**
     * Find a single ingredient by id
     * @param  integer $id the ingredient's id
     * @return object       ingredient object
     */
    public function find($id){

        $query = $this->db->prepare('SELECT * FROM ingredients WHERE id = ?');
        $query->bind_param("s", $id);

        if($query->execute()) {
          $result = $query->get_result();
          $ingredientRow = $result->fetch_assoc();

          $ingredient = $this->ingredientFactory->make($ingredientRow);
          return $ingredient;
        }
        else {
          $query->error;
          echo "\n" . __CLASS__ . "::" . __FUNCTION__ . ":" . $error . "\n";
          return null;
        }

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
        $query = $this->db->prepare('SELECT * FROM ingredients WHERE recipeid = ? ORDER by foodid');
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
      /*

        $query = $this->db->prepare('DELETE FROM recipes WHERE id = ?');
        $query->bind_param("s", $id);

        $bool = $query->execute();
        if($bool) {
          $ingredient->setId($query->insert_id);
        }
        else {
        $query->error;
        echo "\n" . __CLASS__ . "::" . __FUNCTION__ . $error . "\n";
      }

        return $bool;
        */
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
                (foodid, recipeid, quantity, unit_id)
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

      //  return $query->execute();
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
                    foodid = ?,
                    recipeid = ?,
                    quantity = ?,
                    unit_id = ?
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
