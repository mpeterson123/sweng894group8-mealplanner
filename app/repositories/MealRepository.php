<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;
use Base\Factories\MealFactory;
use Base\Factories\RecipeFactory;

class MealRepository extends Repository implements EditableModelRepository {
    private $db,
        $mealFactory;

    public function __construct($db, $mealFactory){
        $this->db = $db;
        $this->mealFactory = $mealFactory;
    }

    /**
     * Returns a given meal based on its id, regardless of household
     * @param interger $id  meal id to be found
     * @return array Associative array of meals
     */
    public function find($id){
        $query = $this->db->prepare('SELECT * FROM meal WHERE id = ?');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $mealRow = $result->fetch_assoc();

        $meal = $this->mealFactory->make($mealRow);
        return $meal;
    }

    /**
     * Calls meal update or insert depending on if it is already in the DB
     * @param meal $meal  meal to be saved
     * @return array Associative array of meals
     */
    public function save($meal){

        $success = false;

        if($meal->getId() && $this->find($meal->getId()))
        {
            $success = $this->update($meal);
        }
        else {
            $success = $this->insert($meal);
        }

        return $success;
    }

    /**
     * Get all meals regardess of household
     * @return array Associative array of meals
     */
    public function all(){
        return $this->db->query('SELECT * FROM meal ORDER by date')->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all meals for a given household regardless of completion status
     * @param  Household $household household to get meal list for
     * @return array Associative array of meals
     */
    public function allForHousehold($household){
        $query = $this->db->prepare('SELECT meal.id, meal.date, meal.addedDate, meal.recipeId, meal.scaleFactor, meal.isComplete
            FROM meal WHERE meal.householdId = ?
            ORDER by date'); //JOIN recipes ON meal.recipeId = recipes.id

        @$query->bind_param("i", $household->getId());

        $query->execute();


        $result = $query->get_result();
        $mealRows = $result->fetch_all(MYSQLI_ASSOC);

        $collection = array();
        foreach($mealRows as $mealRow){
            $collection[] = $this->mealFactory->make($mealRow);
        }

        return $collection;
    }

    /**
     * Get all meals for a household that are incomplete (not yet completed or made)
     * @param  Household $household  household to get meal list for
     * @return array Associative array of meals
     */
    public function incompleteForHousehold($household){
        $query = $this->db->prepare('SELECT meal.id, meal.date, meal.addedDate, meal.recipeId, meal.scaleFactor, meal.isComplete
            FROM meal WHERE meal.householdId = ?
            AND meal.isComplete = 0 ORDER by date'); //JOIN recipes ON meal.recipeId = recipes.id

        @$query->bind_param("i", $household->getId());

        $query->execute();


        $result = $query->get_result();
        $mealRows = $result->fetch_all(MYSQLI_ASSOC);

        $collection = array();
        foreach($mealRows as $mealRow){
            $collection[] = $this->mealFactory->make($mealRow);
        }

        return $collection;
    }

    /**
     * Delete meal from the database
     * @param  meal $meal  meals's id
     * @return bool         Whether query was successful
     */
    public function remove($meal){
        $mealId = $meal->getId();
        $query = $this->db->prepare('DELETE FROM meal WHERE id = ?');
        $query->bind_param("s", $mealId);
        return $query->execute();
    }

    /**
     * Insert item into the database
     * @param  Meal $meal   meal to be stored
     * @return bool Whether query was successful
     */
    public function insert($meal){
        try {
            $query = $this->db
                ->prepare('INSERT INTO meal
                    (date, addedDate, isComplete, recipeId, scaleFactor, householdId, userId)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ');
            @$query->bind_param(
                'ssiidii',
                $meal->getDate(),
                $meal->getAddedDate(),
                $meal->isComplete(),
                $meal->getRecipe()->getId(),
                $meal->getScaleFactor(),
                (new Session())->get('user')->getCurrHousehold()->getId(),
                (new Session())->get('user')->getId()
            );

            if(!$query->execute()){
                return false;
            }
            else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }

    }

    /**
     * Update a meal in database
     * @param  Meal $meal to be updated
     * @return bool Whether query was successful
     */
    public function update($meal){
        try {
            $query = $this->db
                ->prepare('UPDATE meal
                    SET
                        date = ?,
                        addedDate = ?,
                        isComplete = ?,
                        recipeId = ?,
                        scaleFactor = ?
                    WHERE id = ?
                ');

            // translate complete from bool to int
            $tempCompleteInt = 0;
            if ($meal->isComplete() == TRUE){
              $tempCompleteInt = 1;
            }

            @$query->bind_param(
                "ssiidi",
                $meal->getDate(),
                $meal->getAddedDate(),
                $tempCompleteInt,
                $meal->getRecipeId(),
                $meal->getScaleFactor(),
                $meal->getId()
            );


            return $query->execute();
        }
        catch (\Exception $e) {
          return false;
        }
    }

    /**
     * Check if meal is editable by the current household
     * @param  integer $mealId          Meal's id
     * @param  integer $householdId     Current household id
     * @return bool                     Whether the meal is in the user's household
     */
    public function mealBelongsToHousehold($mealId, $householdId)
    {
        $query = $this->db->prepare('SELECT * FROM meal WHERE id = ? AND householdId = ?');
        @$query->bind_param("ii", $mealId, $householdId);

        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

}
