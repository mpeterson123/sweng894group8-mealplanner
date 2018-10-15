<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Models\Ingredient;
use Base\Models\Recipe;
use Base\Repositories\IngredientRepository;
//use Base\Repositories\UnitRepository;

class RecipeFactory {

    private $db;

    public function make($recipeArray)
    {
        //$foodItem = (new FoodItemRepository($this->db))->find($ingredientArray['foodid']);
        //$unit = (new UnitRepository($this->db))->find($ingredientArray['unit_id']);
        //$quantity = new Quantity($ingredientArray['quantity'], $unit);


        $recipe = new Recipe($recipeArray['name'], $recipeArray['description'], $recipeArray['servings'], $recipeArray['source'], $recipeArray['notes']);
        if(isset($recipeArray['id'])){
            $recipe->setId($recipeArray['id']);
        }
        //$ingredient->setFood($ingredientArray['foodid']); //$foodItem);
        //$ingredient->setQuantity($ingredientArray['quantity']); //$quantity);
        //$ingredient->setRecipeId($ingredientArray['recipeid']);

        return $recipe;
    }

    public function __construct($db){
        $this->db = $db;
    }

}
