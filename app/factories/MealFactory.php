<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\Meal;
use Base\Repositories\RecipeRepository;

class MealFactory extends Factory {

    private $recipeRepository;

    public function __construct($recipeRepository){
        $this->recipeRepository = $recipeRepository;

    }

    /**
     * Instantiate Meal
     * @param  array $mealArray     Array of data for a meal
     * @return Meal                 A meal object
     */
    public function make($mealArray):Meal
    {
        $recipe = $this->recipeRepository->find($mealArray['recipeId']);

        $meal = new Meal();

        $meal->setRecipe($recipe);
		$meal->setDate($mealArray['date']);
		$meal->setScaleFactor($mealArray['scaleFactor']);

        // Only existing meals have id, completion information and added date
        if(isset($mealArray['id'])){
            $meal->setId($mealArray['id']);
            $meal->setIsComplete($mealArray['isComplete']);
    		$meal->setAddedDate($mealArray['addedDate']);
        }
        else {
            $meal->setIsComplete(false);
    		$meal->setAddedDate(date('Y-m-d H:i:s'));
        }

        return $meal;
    }

}
