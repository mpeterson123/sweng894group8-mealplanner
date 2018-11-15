<?php
namespace Base\Factories;
require_once __DIR__.'/../../vendor/autoload.php';

use Base\Factories\Factory;
use Base\Models\Ingredient;
use Base\Models\Recipe;
use Base\Repositories\IngredientRepository;
//use Base\Repositories\UnitRepository;

class RecipeFactory extends Factory {

  private $ingredientRepository;

  public function __construct($ingrRepo)
  {
    $this->ingredientRepository = $ingrRepo;
  }

  /**
   * Creates a new instance of Recipe model
   * @param  array    recipeArray - A recipe's properties
   * @return Recipe   A recipe object
   */
       public function make($recipeArray)
    {
        $recipe = new Recipe($recipeArray['name'], $recipeArray['directions'], $recipeArray['servings'], $recipeArray['source'], $recipeArray['notes']);

        if(isset($recipeArray['id'])){
            $recipe->setId($recipeArray['id']);

            //Retrieve the ingredients associated with this recipe
            $currentIngreds = $this->ingredientRepository->allForRecipe($recipe->getId());

            //Add the ingredient objects to the recipe object
            for($i=0;$i<count($currentIngreds);$i++) {
              $recipe->addIngredient($currentIngreds[$i]);
            }
        }

        return $recipe;
    }



}
