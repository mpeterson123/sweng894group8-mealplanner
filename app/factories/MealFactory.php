<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Meal;
use Base\Repositories\RecipeRepository;

class MealFactory {

    private $recipeRepository;

    public function __construct($recipeRepository){
        $this->recipeRepository = $recipeRepository;

    }

    public function make($mealArray):Meal
    {
        $recipe = $this->recipeRepository->find($mealArray['recipe']);

        $newMeal = new Meal($recipe,$mealArray['date'],$mealArray['scaleFactor']);
        if(isset($mealArray['id'])){
            $newMeal->setId($mealArray['id']);
        }

        return $newMeal;
    }

}
