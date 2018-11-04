<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Ingredient;
use Base\Models\Quantity;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;

class IngredientFactory extends Factory {

    private $foodItemRepository,
        $unitRepository;

    public function __construct($foodItemRepository, $unitRepository){
        $this->foodItemRepository= $foodItemRepository;
        $this->unitRepository= $unitRepository;
    }

    public function make($ingredientArray)
    {
        $foodItem = $this->foodItemRepository->find($ingredientArray['foodid']);
        $unit = $this->unitRepository->find($ingredientArray['unit_id']);
        $quantity = new Quantity($ingredientArray['quantity'], $unit);

        $ingredient = new Ingredient($foodItem, $quantity, $ingredientArray['recipeid'], $unit);
        if(isset($ingredientArray['id'])){
            $ingredient->setId($ingredientArray['id']);
        }

        return $ingredient;
    }



}
