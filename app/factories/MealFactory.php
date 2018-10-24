<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Meal;
use Base\Repositories\MealRepository;

class MealFactory {

    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function make(array $mealArray):Meal
    {
        $newMeal = new Meal();
        if(isset($mealArray['id'])){
            $newMeal->setId($mealArray['id']);
        }

        $newMeal->createMeal($mealArray['recipe'],$mealArray['date'],$mealArray['scale']);

        return $newMeal;
    }

}
