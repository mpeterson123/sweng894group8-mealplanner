<?php
namespace Base\Repositories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Repositories\Repository;
use Base\Helpers\Session;
use Base\Factories\MealFactory;

class MealRepository extends Repository {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function find($id){

        $query = $this->db->prepare('SELECT * FROM meal WHERE id = ?');
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $mealRow = $result->fetch_assoc();

        $meal = (new MealFactory($this->db))->make($mealRow);
        return $meal;
    }

    public function save($meal){

        if($meal->getId() && $this->find($meal->getId()))
        {
            $this->update($meal);
        }
        else {
            $this->insert($meal);
        }
    }

    public function all(){
        return $this->db->query('SELECT * FROM meal ORDER by date')->fetch_all(MYSQLI_ASSOC);
    }

    public function allForUser($user){
        $query = $this->db->prepare('SELECT meal.id, meal.date, meal.addedDate, meal.recipe, meal.scaleFactor, meal.isComplete FROM meal JOIN recipes ON meal.recipe = recipes.id WHERE user_id = ? ORDER by name');
        @$query->bind_param("i", $user->getId());
        $query->execute();

        $result = $query->get_result();
        $mealRows = $result->fetch_all(MYSQLI_ASSOC);

        $collection = array();
        $mealFactory = new MealFactory($this->db);
        foreach($mealRows as $mealRow){
            $collection[] = $MealFactory->make($mealRow);
        }

        return $collection;
    }

    public function remove($id){
        $query = $this->db->prepare('DELETE FROM meal WHERE id = ?');
        $query->bind_param("s", $id);
        return $query->execute();
    }

    protected function insert($meal){
        $query = $this->db
            ->prepare('INSERT INTO meal
                (id, date, addedDate, isComplete, recipe, scaleFactor)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ');

        @$query->bind_param(
            $meal->getId(),
            $meal->getDate(),
            $meal->getAddedDate(),
            $meal->isComplete(),
            $meal->getRecipeId(),
            $meal->getScale()
        );

        return $query->execute();
    }

    protected function update($meal){
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

        $query->execute();
    }

    public function mealBelongsToUser($mealId, $user)
    {
        $query = $this->db->prepare('SELECT * FROM meal JOIN recipes ON meal.recipe = recipes.id WHERE recipes.user_id = ? AND meal.id = ?');
        $query->bind_param(
            $user->getId(),
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
