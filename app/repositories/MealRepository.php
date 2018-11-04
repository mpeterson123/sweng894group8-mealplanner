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

    public function find($id){
        $query = $this->db->prepare('SELECT * FROM meal WHERE id = ?');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $mealRow = $result->fetch_assoc();

        $meal = $this->mealFactory->make($mealRow);
        return $meal;
    }

    public function save($meal){

        $success = false;
        if($this->find($meal->getId()))
        {
            $success = $this->update($meal);
        }
        else {
            $success = $this->insert($meal);
        }

        return $success;
    }

    public function all(){
        return $this->db->query('SELECT * FROM meal ORDER by date')->fetch_all(MYSQLI_ASSOC);
    }

    public function allForHousehold($household){
        $query = $this->db->prepare('SELECT meal.id, meal.date, meal.addedDate, meal.recipe, meal.scaleFactor, meal.isComplete FROM meal JOIN recipes ON meal.recipe = recipes.id WHERE recipes.householdId = ? ORDER by date');

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

    public function remove($id){
        $query = $this->db->prepare('DELETE FROM meal WHERE id = ?');
        $query->bind_param("s", $id);
        return $query->execute();
    }

    public function insert($meal){
        try {
            $query = $this->db
                ->prepare('INSERT INTO meal
                    (date, addedDate, isComplete, recipe, scaleFactor, householdId, userId)
                    VALUES (?, ?, ?, ?, ?)
                ');
            @$query->bind_param(
                $meal->getDate(),
                $meal->getAddedDate(),
                $meal->isComplete(),
                $meal->getRecipe()->getId(),
                $meal->getScale(),
                $this->session->get('user')->getHouseholds()[0],
                $household = $this->session->get('user')->getId()
            );

            return $query->execute();
        } catch (\Exception $e) {
            return false;
        }

    }

    public function update($meal){
      try {
        $query = $this->db
            ->prepare('UPDATE meal
                SET
                    date = ?,
                    addedDate = ?,
                    isComplete = ?,
                    recipe = ?,
                    scaleFactor = ?
                WHERE id = ?
            ');

        @$query->bind_param(
            $meal->getDate(),
            $meal->getAddedDate(),
            $meal->isComplete(),
            $meal->getRecipeId(),
            $meal->getScale(),
            $meal->getId()
        );

        return $query->execute();
      } catch (\Exception $e) {
          return false;
        }
    }

    public function mealBelongsToHousehold($mealId)
    {
        $householdId = $this->session->get('user')->getHouseholds()[0];
        $query = $this->db->prepare('SELECT * FROM meal JOIN recipes ON meal.recipe = recipes.id WHERE meal.householdId = ? AND meal.id = ?');
        $query->bind_param(
            $householdId,
            $mealId
        );
        $query->execute();

        $result = $query->get_result();
        if($result->num_rows > 0){
            return true;
        }
        return false;
    }

}
