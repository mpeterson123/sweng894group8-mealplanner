<?php
namespace Base\Controllers;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';


//////////////////////
// Standard classes //
//////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;
use Base\Helpers\Redirect;
use Base\Helpers\Format;
use \Valitron\Validator;

///////////////////////////
// File-specific classes //
///////////////////////////
use Base\Models\Recipe;
use Base\Repositories\RecipeRepository;
use Base\Repositories\IngredientRepository;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;
use Base\Factories\RecipeFactory;
use Base\Factories\IngredientFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;
use Base\Repositories\CategoryRepository;
use Base\Repositories\GroceryListItemRepository;
use Base\Factories\GroceryListItemFactory;
use Base\Repositories\MealRepository;
use Base\Helpers\Log;

/**
 * Handles recipe creation and management
 */
class Recipes extends Controller {

    protected $dbh,
        $session,
        $request,
        $log;

    private $unitRepository,
        $recipeFactory,
        $recipeRepository,
        $foodItemRepository,
        $ingredientFactory,
        $ingredientRepository,
        $groceryListItemFactory,
        $groceryListItemRepository,
        $mealRepository;

    public function __construct($dependencies){
		$this->dbh = $dependencies['dbh'];
		$this->session = $dependencies['session'];
		$this->request = $dependencies['request'];
		$this->log = $dependencies['log'];

        $this->unitRepository = $dependencies['unitRepository'];
        $this->foodItemRepository = $dependencies['foodItemRepository'];
        $this->ingredientFactory = $dependencies['ingredientFactory'];
        $this->ingredientRepository = $dependencies['ingredientRepository'];
        $this->recipeFactory = $dependencies['recipeFactory'];
        $this->recipeRepository = $dependencies['recipeRepository'];
        $this->groceryListItemRepository = $dependencies['groceryListItemRepository'];
        $this->groceryListItemFactory = $dependencies['groceryListItemFactory'];
        $this->mealRepository = $dependencies['mealRepository'];

    }

    /**
     * Show page listing recipes in user's current household
     */
    public function index():void{
        $household = $this->session->get('user')->getCurrHousehold();
        $foodItemCount = $this->foodItemRepository->countForHousehold($household);
        $recipes = $this->recipeRepository->allForHousehold($household);

        $this->view('recipe/index', compact('recipes', 'foodItemCount'));
    }

    /**
     * Show page to edit existing recipe
     * @param integer $id Recipe id
     */
    public function edit($id):void{
        $household = $this->session->get('user')->getCurrHousehold();

        // Get user's fooditems and list of units
        $foodItems = $this->foodItemRepository->allForHousehold($household);
        $units = $this->unitRepository->all();

        // Get recipe Object
        $recipe = $this->recipeRepository->find($id);

        $this->view('recipe/edit', compact('recipe', 'foodItems', 'units'));
    }

    /**
     * Show page for creating recipe
     * @return [type] [description]
     */
    public function create():void{
        $currentHousehold = $this->session->get('user')->getCurrHousehold();
        $this->checkHasFoodItems($currentHousehold);

        // Get user's foodItems and list of units
        $foodItems = $this->foodItemRepository->allForHousehold($currentHousehold);
        $units = $this->unitRepository->all();

        $this->session->flushOldInput();

        $this->view('recipe/create', compact('foodItems', 'units'));
    }

    /**
     * Save a new recipe to the DB
     */
    public function store():void {
        try {

            $currentHousehold = $this->session->get('user')->getCurrHousehold();

            // Make sure user has food item
            $this->checkHasFoodItems($currentHousehold);

            $input = $this->request;

            // Validate input
            $this->validateInput($input, 'create');

            //Use a RecipeFactory to create the Recipe Object:
            $recipe = $this->recipeFactory->make($input);

            $this->dbh->getDB()->begin_transaction();


            //If the recipe is already in the database, go back
            if ($this->recipeRepository->findRecipeByName($recipe->getName())) {
                throw new \Exception("A recipe with this name already exists", 1);
            }

            // Save the recipe in the database:
            if(!$this->recipeRepository->save($recipe)){
                throw new \Exception("Error saving recipe to DB", 2);
            }

            //Add the ingredients
            $this->addIngredients($input, $recipe);

            // Commit
            $this->dbh->getDB()->commit();
            // Flash success message
            $this->session->flashMessage('success', ucfirst($recipe->getName()).' was added to your recipes.');

            // Redirect back after creating
            Redirect::toControllerMethod('Recipes', 'index');
            return;
        }
        catch (\Exception $e){
            $this->dbh->getDB()->rollback();

            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', $e->getMessage());

            $message = '';
            if($e->getCode() == 1){
                $message = ' '.$e->getMessage();
            }
            $this->session->flashMessage('danger', 'Sorry, something went wrong. Your recipe could not be saved.'.$message);

            // Redirect back after creating
            Redirect::toControllerMethod('Recipes', 'create');
            return;
        }

        // Redirect back after creating
        Redirect::toControllerMethod('Recipes', 'create');
        return;

    }

    /**
     * Add ingredients to a recipe
     * @param array $in     Array of ingredients
     * @param Recipe $rec   The recipe the ingredients will be added to
     */
    private function addIngredients($in, $recipe) {
        $user = $this->session->get('user');

        // If new items in input
        if(isset($in['newFoodId'])) {
            // Iterate over all ingredients
            for($i = 0; $i < count($in['newFoodId']); $i++) {

                //Create the ingredient array:
                $ingredientInput = array("foodId" => $in['newFoodId'][$i],
                                      "quantity" => $in['newQuantity'][$i],
                                      "recipeId" => $recipe->getId(),
                                      "unitId" => $in['newUnitId'][$i]);

                //Create the ingredient object:
                $ingredient = $this->ingredientFactory->make($ingredientInput);

                if(!$ingredient){
                    throw new \Exception("Please check your ingredients are valid", 1);
                }

                // Check units are compatible
                if($ingredient->getQuantity()->getUnit()->getBaseUnit()
                    != $ingredient->getFood()->getUnit()->getBaseUnit()){

                    $this->log->add($user->getId(), 'Error',
                        'Ingredients - Incompatible units. Unable to add '
                        .ucfirst($ingredient->getFood()->getName()));
                    $this->session->flashMessage(
                        'danger', 'Sorry, something went wrong. '
                        . ucfirst($ingredient->getFood()->getName())
                        . ' was not added to your ingredients because its units
                        are incompatible with the food item\'s units');

                    throw new \Exception("Please check your ingredients are compatible with their food items", 1);

                }

                // Check for duplicate ingredients
                if($this->ingredientRepository->findIngredientByFoodId($ingredient->getFood()->getId(), $ingredient->getRecipeId()) == null) {

                    //Add the ingredient to the recipe object:
                    $recipe->addIngredient($ingredient);

                    // Save the ingredient in the database:
                    if(!$this->ingredientRepository->save($ingredient)) {
                        throw new \Exception(ucfirst($ingredient->getFood()->getName()) . ' could not be saved to DB.', 1);
                    }
                }
                else {
                    throw new \Exception(ucfirst($ingredient->getFood()->getName()) . ' is duplicated in your recipe.', 1);
                }
            } //end for
        } //end if new items were returned
    }

    /**
     * Delete a recipe
     * @param integer $id Id of recipe to delete
     */
    public function delete($id):void{
            $user = $this->session->get('user');
            $household = $user->getCurrHousehold();

            //Remove the recipe from the recipes table:
            $recipe = $this->recipeRepository->find($id);

            // If recipe doesn't exist, or if belong to household, do not delete
            if(!$recipe || !$this->recipeRepository->recipeBelongsToHousehold($id, $household)){
                $this->session->flashMessage('danger', 'The recipe could not be deleted. It does not belong to your household or does not exist.');
                $this->log->add($user->getId(), 'Error', 'Recipe Delete - Recipe doesn\'t belong to this household or does not exist');
                Redirect::toControllerMethod('Recipes', 'index');
                return;
            }

            if($this->recipeRepository->remove($id))
            {
              $this->session->flashMessage('success', $recipe->getName().' was removed from your recipes.');
            }
            else {
              $this->log->add($user->getId(), 'Error', 'Recipe Delete - Recipe could not be removed');
              $this->session->flashMessage('danger', 'Sorry, something went wrong. ' . $recipe->getName().' was not removed from your recipes.');
            }



            // Redirect to list after deleting
            Redirect::toControllerMethod('Recipes', 'index');
            return;
    }

    /**
     * Update a recipe in the DB
     * @param integer $id Id of recipe to update
     */
    public function update($id):void {

        $recipe = $this->recipeRepository->find($id);

        $this->checkRecipeBelongsToHousehold($id);

        $input = $this->request;

        $this->validateInput($input, 'edit', [$id]);

        $recipe->setId($id);
        $recipe->setName($input['name']);
        $recipe->setDirections($input['directions']);
        $recipe->setServings($input['servings']);
        $recipe->setSource($input['source']);
        $recipe->setNotes($input['notes']);


        $this->dbh->getDB()->begin_transaction();
        try {
            // Save the recipe in the database:
            if ($this->recipeRepository->save($recipe)) {

                //Update the existing ingredients
                $this->updateIngredients($input, $recipe);

                //Add new ingredients
                $this->addIngredients($input, $recipe);

                // Commit
                $this->dbh->getDB()->commit();

                // Update Grocery List after ingredient update
                $this->reconcileGroceryList($recipe);

                $this->session->flashMessage('success', ucfirst($recipe->getName()).' was updated.');

            }
        }
        catch (\Exception $e){
            $this->dbh->getDB()->rollback();

            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', $e->getMessage());

            $message = '';
            if($e->getCode() == 1){
                $message = ' '.$e->getMessage();
            }
            $this->session->flashMessage('danger', 'Sorry, something went wrong. Your recipe could not be saved.'.$message);
        }

        Redirect::toControllerMethod('Recipes', 'edit', array('id'=> $id));

        return;
    }

    /**
     * Update a recipe's ingredients in the DB
     * @param array $in  2D Array of ingredients
     * @param Recipe $rec Recipe to update ingredients for
     */
    private function updateIngredients($in, $rec):void {

        //Get the ingredients associated with this recipe:
        $currentIngredients = $rec->getIngredients();

        //If existing ingredients were received from the view:
        if(isset($in['ingredientIds'])) {

            // Loop through the ingredients currently in the database, if it doesn't exist in the ingredients returned
            // from the view, remove it from the database:
            for( $i = 0; $i < count($currentIngredients); $i++) {

                $ingredientStillInRecipe = in_array($currentIngredients[$i]->getId(), $in['ingredientIds']);

                // If ingredient is not in recipe anymore
                if(!$ingredientStillInRecipe) {
                    $this->ingredientRepository->remove($currentIngredients[$i]->getId());

                    // Also remove it from the recipe object
                    $rec->removeIngredient($currentIngredients[$i]->getFood()->getName());
                }
            }

            // Loop through the ingredients received from the view, update them in
            // the database, and update them in the recipe object:
            for($i = 0; $i < count($in['ingredientIds']); $i++){

                //Create the ingredient array:
                $ingredientInput = array("foodId" => $in['foodId'][$i],
                                    "quantity" => $in['quantity'][$i],
                                    "recipeId" => $rec->getId(),
                                    "unitId" => $in['unitId'][$i],
                                    "id" => $in['ingredientIds'][$i]);

                //Create the ingredient object:
                $ingredient = $this->ingredientFactory->make($ingredientInput);

                if(!$ingredient){
                    throw new \Exception("Error making ingredient object", 2);
                }

                //Update the ingredient in the recipe object
                $rec->updateIngredient($ingredient);

                //Save the ingredient in the database:
                if(!$this->ingredientRepository->save($ingredient)) {
                    throw new \Exception("Ingredient could not be udated in DB", 2);
                }
            }
        } //end if isset($in['ingredientIds'])
        else {
            //No existing ingredients were returned from the view.
            //If there are ingredients associated with the recipe, they need to be removed:
            if($currentIngredients) {
                for($i=0;$i<count($currentIngredients);$i++) {
                    $this->ingredientRepository->remove($currentIngredients[$i]->getId());

                    //Also remove them from the recipe object
                    $rec->removeIngredient($currentIngredients[$i]->getFood()->getName());
                }
            }
        }
    }

    /**
     * Checks wether a recipe belongs to a household
     * @param integer $id Recipe id
     */
    public function checkRecipeBelongsToHousehold($id):void{
        $household = $this->session->get('user')->getCurrHousehold();

        // If recipe doesn't belong to household, show forbidden error
        if(!$this->recipeRepository->recipeBelongsToHousehold($id, $household)){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }

    /**
     * Validates food item input from user form
     * @param array $input  [description]
     * @param string $method Method to redirect to
     * @param array $params Parameters for the redirection method
     */
    private function validateInput($input, $method, $params = NULL):void{
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z\s\n\r.,!#\/\(\)-:]+$/i';

        $rules = [
            'required' => [
                ['name'],
                ['servings'],
            ],

            'optional' => [
              ['newFoodId'],
              ['newQuantity'],
              ['newUnitId'],
              ['recipeId'],
              ['ingredientIds'],
              ['quantity'],
              ['foodId'],
              ['unitId']
            ],

            'integer' => [
                ['newFoodId.*'],
                ['newUnitId.*'],
                ['recipeId'],
                ['foodId.*'],
                ['unitId.*'],
                ['ingredientIds.*']
            ],

            'regex' => [
                ['name', $safeStringRegex],
                ['directions', $safeStringRegex],
                ['servings', $twoSigDigFloatRegex],
                ['source', $safeStringRegex],
                ['notes', $safeStringRegex]

            ],

            'min' => [
              ['servings', 0.05],
              ['newFoodId.*', 1],
              ['newUnitId.*', 1],
              ['newQuantity.*', 0.05],
              ['quantity.*', 0.05]
            ],
            'max' => [
                ['servings', 9999],
            ],
            'lengthMax' => [
                ['name', 128],
                ['directions', 65535],
                ['source', 64],
                ['notes', 128]
            ],

        ];

        $validator->rules($rules);

        $validator->labels(array(
            'newFoodId.*' => 'FoodItem',
            'foodId.*' => 'FoodItem',
            'newUnitId.*' => 'Unit',
            'unitId.*' => 'Unit',
            'newQuantity.*' => 'Ingredient Quantity',
            'quantity.*' => 'Ingredient Quantity'
        ));


        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Recipes', $method, $params);
            return;
        }
    }

    /**
     * Checks whether user has food items
     * @param Household $household Household to check
     */
    private function checkHasFoodItems($household):void {
        if($this->foodItemRepository->countForHousehold($household) == 0){
            $this->session->flashMessage('warning',
                "You must create a food item before adding recipes.");
            Redirect::toControllerMethod('FoodItems', 'create');
            return;
        }
    }

    /**
     * Update grocery list in the DB
     * @param $recipeToUpdate $recipe being updated to update in grocery list
     */
    private function reconcileGroceryList($recipeToUpdate):void {
        foreach ($recipeToUpdate->getIngredients() as $ingredient) {
            // Convert unit
            $ingredient->getQuantity()->convertTo($ingredient->getFood()->getUnit());

            // If ingredient value has changed
            if ($ingredient->getQuantity()->getValue() != $this->ingredientRepository->find($ingredient->getId())->getQuantity()->getValue()) {
                // Get item's current qty to purchase from grocery list
                $groceryListItem = $this->groceryListItemRepository->findByFoodId($ingredient->getFood()->getId());

                // Find all meals using affceted recipe, then the count
                $mealUpdateList = $this->mealRepository->findMealsByRecipeId($recipeToUpdate->getId());
                $mealCount = count($mealUpdateList);
                $amountToAdd = 0;

                foreach($mealUpdateList as $meal){

                  // Set original recipe quantity converted to food item's unit, times scale factor
                  $ingredientQuantity = $ingredient->getQuantity()->getValue() * $meal->getScaleFactor();
                  $amountToAdd = $amountToAdd + $ingredientQuantity - $ingredient->getFood()->getStock();

                }

                // If the grocery list item does not exist, simply add the scaled ingredient quantity minus the current stock to grocery list
                if(!$groceryListItem){
                    // If item is not overstocked (if quantity to add is more than the quantity in stock)
                    if($amountToAdd > 0.01){
                        $newGroceryListItemData = array(
                            'foodItemId' => $ingredient->getFood()->getId(),
                            'amount' => $amountToAdd
                        );
                        $groceryListItem = $this->groceryListItemFactory->make($newGroceryListItemData);

                        if(!$this->groceryListItemRepository->save($groceryListItem)){
                            throw new \Exception("Unable to add '{$ingredient->getFood()->getName()}' to grocery list", 1);
                        };
                    }
                }
                // Otherwise, get new grocery list quantity
                else {
                    if($amountToAdd > 0.01){
                        $groceryListItem->setAmount($amountToAdd);

                        if(!$this->groceryListItemRepository->save($groceryListItem)){
                            $user = $this->session->get('user');
                            $this->log->add($user->getId(), 'Error', 'Save Meal - Unable to update grocery list');
                            throw new \Exception("Unable to update '{$ingredient->getFood()->getName()}' in grocery list", 2);
                        }
                    }
                }
            }
            else
            {
              // New quantity and old quantity match. Do not update.
            }
        }
    }
}
