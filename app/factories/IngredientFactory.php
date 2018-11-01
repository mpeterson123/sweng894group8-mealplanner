<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Ingredient;
use Base\Models\Quantity;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;

class IngredientFactory {

    // TODO Eliminate db
    private $db;

    // TODO find all references of factory and make injections
    public function __construct($db, $foodItemRepository, $unitRepository){
        $this->db = $db;
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
