<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Ingredient;
use Base\Models\Quantity;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;

/**
 * Handles ingredient creation
 */
class IngredientFactory extends Factory {

    private $foodItemRepository,
        $unitRepository;

    public function __construct($foodItemRepository, $unitRepository){
        $this->foodItemRepository= $foodItemRepository;
        $this->unitRepository= $unitRepository;
    }

    /**
     * Creates a new instance of Ingredient model
     * @param  array    $ingredientArray A ingredient's properties
     * @return Ingredient                A ingredient object
     */
    public function make($ingredientArray)
    {
        $foodItem = $this->foodItemRepository->find($ingredientArray['foodId']);
        $unit = $this->unitRepository->find($ingredientArray['unitId']);
        $quantity = new Quantity($ingredientArray['quantity'], $unit);

        $ingredient = new Ingredient($foodItem, $quantity, $ingredientArray['recipeId'], $unit);
        if(isset($ingredientArray['id'])){
            $ingredient->setId($ingredientArray['id']);
        }

        return $ingredient;
    }



}
