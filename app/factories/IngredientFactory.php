<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Ingredient;
use Base\Models\Quantity;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;

class IngredientFactory {

    private $db;

    public function make($ingredientArray)
    {
        $foodItem = (new FoodItemRepository($this->db))->find($ingredientArray['foodid']);
        $unit = (new UnitRepository($this->db))->find($ingredientArray['unit_id']);
        $quantity = new Quantity($ingredientArray['quantity'], $unit);


        $ingredient = new Ingredient($foodItem, $quantity, $ingredientArray['recipeid'], $unit);
        if(isset($ingredientArray['id'])){
            $ingredient->setId($ingredientArray['id']);
        }

        return $ingredient;
    }

    public function __construct($db){
        $this->db = $db;
    }

}
