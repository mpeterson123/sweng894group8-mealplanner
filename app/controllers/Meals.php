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
use Base\Models\Meal;
use Base\Repositories\MealRepository;
use Base\Repositories\RecipeRepository;
use Base\Repositories\CategoryRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\IngredientRepository;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\GroceryListItemRepository;
use Base\Factories\MealFactory;
use Base\Factories\RecipeFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;
use Base\Factories\IngredientFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\GroceryListItemFactory;
use Base\Helpers\Log;

class Meals extends Controller {

    protected $dbh,
        $session,
        $request,
        $log;

    private $mealRepository,
        $mealFactory,
        $recipeRepository,
        $groceryListItemFactory,
        $groceryListItemRepository,
        $foodItemRepository;

    public function __construct(DatabaseHandler $dbh, Session $session, $request){
  		$this->dbh = $dbh;
  		$this->session = $session;
  		$this->request = $request;
        $this->log = new Log($this->dbh);

        // TODO Use dependency injection
        $categoryFactory = new CategoryFactory($this->dbh->getDB());
        $categoryRepository = new CategoryRepository($this->dbh->getDB(), $categoryFactory);

        $unitFactory = new UnitFactory($this->dbh->getDB());
        $unitRepository = new UnitRepository($this->dbh->getDB(), $unitFactory);

        $foodItemFactory = new FoodItemFactory($categoryRepository, $unitRepository);
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $foodItemFactory);

        $ingredientFactory = new IngredientFactory($this->foodItemRepository, $unitRepository);
        $ingredientRepository = new IngredientRepository($this->dbh->getDB(), $ingredientFactory);

        $recipeFactory = new RecipeFactory($ingredientRepository);
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB(), $recipeFactory);

        $this->mealFactory = new MealFactory($this->recipeRepository);
        $this->mealRepository = new MealRepository($this->dbh->getDB(), $this->mealFactory);


        $this->groceryListItemFactory = new GroceryListItemFactory($this->foodItemRepository);
        $this->groceryListItemRepository = new GroceryListItemRepository($this->dbh->getDB(), $this->groceryListItemFactory);

    }

    public function index():void{
        $household = $this->session->get('user')->getCurrHousehold();
        $recipeCount = $this->recipeRepository->countForHousehold($household);
        $meals = $this->mealRepository->incompleteForHousehold($household);

        // TODO Need to figure out how to toggle between entire list and not completed list
        // $meals = $this->mealRepository->allForHousehold($household);

        $this->view('meal/index', compact('meals', 'recipeCount'));
    }

    /**
     * Show page for editing an existing meal
     * @param integer $id Meal id
     */
    public function edit($id):void{

        // Get all recipes in household, for edit dropdown recipe selection
        $household = $this->session->get('user')->getCurrHousehold();
        $recipes = $this->recipeRepository->allForHousehold($household);

        // Get meal by ID
        $meal = $this->mealRepository->find($id);

        $this->view('meal/edit', compact('meal','recipes'));
    }

    /**
     * Show page for viewing an existing meal
     * @param integer $id Meal id
     */
    public function show($id):void{

        // Get meal by ID
        $meal = $this->mealRepository->find($id);

        $this->view('meal/show', compact('meal'));
    }

    /**
     * Show page for scheduling a new meal
     */
    public function create():void{
        $currentHousehold = $this->session->get('user')->getCurrHousehold();
        $this->checkHasRecipes($currentHousehold);

        $recipes = $this->recipeRepository->allForHousehold($currentHousehold);

        $this->view('meal/create', compact('recipes'));

    }

    /**
     * Save a new meal to the DB
     */
    public function store():void{
        $currentHousehold = $this->session->get('user')->getCurrHousehold();
        $this->checkHasRecipes($currentHousehold);

        $input = $this->request;
        $this->session->flashOldInput($input);

        // Validate input
        $this->validateCreateInput($input, 'create');

        // Check if recipe belongs to the user's household
        if(!$this->recipeRepository->recipeBelongsToHousehold($input['recipeId'], $currentHousehold)) {
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Meal Store - Recipe doesn\'t belong to this household ('.$currentHousehold->getId().')');
            $this->session->flashMessage('danger', 'Uh oh. The recipe you selected does not belong to your household.');
            Redirect::toControllerMethod('Meals', 'create');
        };

        // Change date to correct format
        $input['date'] = Format::date($input['date']);

        // Make meal
        $meal = $this->mealFactory->make($input);

        // Save to DB
        $this->dbh->getDB()->begin_transaction();
        try {
            $this->saveMealAndUpdateGroceryList($meal);
            $this->dbh->getDB()->commit();
        }
        catch (\Exception $e){
            // TODO Log error (use $e->getMessage())
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Meal - Unable to save');
            $this->dbh->getDB()->rollback();
            $this->session->flashMessage('danger', 'Uh oh, something went wrong. Your meal could not be saved.'.$e->getMessage());
            Redirect::toControllerMethod('Meals', 'create');
        }

        // Flash success message and flush old input
        $this->session->flashMessage('success', ucfirst($meal->getRecipe()->getName()).' was added to your meal plan.');
        $this->session->flushOldInput();

        // Redirect back after updating
        Redirect::toControllerMethod('Meals', 'index');
        return;
    }

    /**
     * Delete a meal
     * @param integer $id Meal id
     */
    public function delete($id):void{
        $meal = $this->mealRepository->find($id);
        $user = $this->session->get('user');

        // If meal doesn't exist, load 404 error page
        if(!$meal){
            $this->log->add($user->getId(), 'Error', 'Meal Delete - Meal doesn\'t exist');
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }

        $currentHousehold = $this->session->get('user')->getCurrHousehold();

        if($this->mealRepository->mealBelongsToHousehold($id,$currentHousehold->getId()))
        {
          $this->mealRepository->remove($meal);
          $this->session->flashMessage('success: meal with date of ', $meal->getDate().' was removed.');
        }
        else
        {
          $this->log->add($user->getId(), 'Error', 'Meal Delete - Meal doesn\'t belong to this household (HH: '.$currentHousehold->getId().')');
          $this->session->flashMessage('danger', 'Uh oh. The meal you selected does not belong to your household.');
        }

        // Redirect to list after deleting
        Redirect::toControllerMethod('Meals', 'index');
        return;
    }

    /**
     * Update a meal in the DB
     * @param integer $id Meal id
     */
    public function update($id):void{
        $currentHousehold = $this->session->get('user')->getCurrHousehold();

        try {
            if(!$this->mealRepository->mealBelongsToHousehold($id,$currentHousehold->getId()))
            {
                throw new \Exception("Meal doesn't belong to household", 1);
            }

            $input = $this->request;
            $this->validateEditInput($input, 'edit', [$id]);

            $meal = $this->mealRepository->find($id);
            $meal->setId(intval($id));
            $meal->setScaleFactor($input['scaleFactor']);
            // Change date to correct format
            $input['date'] = Format::date($input['date']);
            $meal->setDate($input['date']);
            $recipe = $this->recipeRepository->find($input['recipeId']);
            $meal->setRecipe($recipe);

            if(!$this->mealRepository->save($meal)){
                throw new \Exception("Error saving meal to DB", 1);
            };

            // Flash success message
            $this->session->flashMessage('success', 'Your '.$meal->getRecipe()->getName().' meal for '.$meal->getDate(true).' was updated.');

            // Redirect back after updating
            Redirect::toControllerMethod('Meals', 'edit', array('id' => $id));
            return;
        }
        catch(\Exception $e){
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Meal Update -'.$e->getMessage());
            $this->session->flashMessage('danger', 'Uh oh. Your meal could not be updated.');

            // Redirect back after updating
            Redirect::toControllerMethod('Meals', 'edit', array('id' => $id));
            return;
        }
    }

    /**
     * Validates user input from meal creation form
     * @param array $input  	Input to validate
     * @param string $method 	Method to redirect to
     * @param array $params 	Parameters for the redirection method
     */
    private function validateCreateInput($input, $method, $params = NULL):void{
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $rules = [
            'required' => [
                ['recipeId'],
                ['date'],
                ['scaleFactor']
            ],
            'dateFormat' => [
                ['date', 'm/d/Y']
            ],
            'regex' => [
                ['scaleFactor', $twoSigDigFloatRegex]
            ]
        ];
        $validator->rules($rules);
        $validator->labels(array(
            'scaleFactor' => 'Scale Factor',
            'recipeId' => 'Recipe'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Meals', $method, $params);
            return;
        }
    }


    /**
     * Validates user input from meal editing form
     * @param array $input  	Input to validate
     * @param string $method 	Method to redirect to
     * @param array $params 	Parameters for the redirection method
     */
    private function validateEditInput($input, $method, $params = NULL):void{
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            'required' => [
                ['recipeId'],
                ['date'],
                ['scaleFactor']
            ],
            'regex' => [
                ['scaleFactor', $twoSigDigFloatRegex]
            ]
        ];
        $validator->rules($rules);

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Meals', $method, $params);
            return;
        }
    }

    /**
     * Complete a meal
     * @param integer $id Meal id
     */
    public function complete($id):void {
        $this->dbh->getDB()->begin_transaction();
        try {
            $meal = $this->mealRepository->find($id);
            $currentHousehold = $this->session->get('user')->getCurrHousehold();

            if(!$this->mealRepository->mealBelongsToHousehold($id,$currentHousehold->getId()))
            {
                throw new \Exception("Meal doesn't belong to household", 1);
            }

            // Update Food items from ingredients from recipe from meal
            foreach($meal->getRecipe()->getIngredients() as $ingredient) {
            	$foodItem = $ingredient->getFood();

                // Convert to food item's unit
                $ingredient->getQuantity()->convertTo($foodItem->getUnit());

                // Reduce stock by recipe ingredient quantity * scale factor
                $newStock = $foodItem->getStock() - ($ingredient->getQuantity()->getValue() * $meal->getScaleFactor());

                if($newStock < 0){
                    $newStock = 0;
                }

                // Save new stock and completed meal
                $foodItem->setStock($newStock);
                if(!$this->foodItemRepository->save($foodItem)){
                    throw new \Exception("Unable to update stock for ".$foodItem->getName().' in DB', 1);
                };
            }

            $meal->complete();
            if(!$this->mealRepository->save($meal)){
                throw new \Exception("Unable to update meal in DB", 1);
            };

            // Commit here
            $this->dbh->getDB()->commit();

            // Flash success message
            $this->session->flashMessage('success','Your '.ucfirst($meal->getRecipe()->getName()).' meal with date of '.$meal->getDate().' was completed. Your food items\' stock have also been updated.');

            // Redirect back with errors
            Redirect::toControllerMethod('Meals', 'index');
            return;
        }
        catch (\Exception $e){
            // Rollback changes
            $this->dbh->getDB()->rollback();

            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'The meal was not completed');
            $this->session->flashMessage('danger','Uh oh. An error occurred. Your meal could not be completed.');

            Redirect::toControllerMethod('Meals', 'index');
            return;
        }
    }

    /**
     * Save a meal and update grocery list according to ingredients required
     * @param  Meal $meal   Meal to be added/updated
     */
    private function saveMealAndUpdateGroceryList($meal):void{
        foreach ($meal->getRecipe()->getIngredients() as $ingredient) {
            // Set original recipe quantity converted to food item's unit, times scale factor
            $ingredient->getQuantity()->convertTo($ingredient->getFood()->getUnit());
            $ingredientQuantity = $ingredient->getQuantity()->getValue() * $meal->getScaleFactor();

            // Get item's current qty to purchase from grocery list
            $groceryListItem = $this->groceryListItemRepository->findByFoodId($ingredient->getFood()->getId());

            // If the grocery list item does not exist, simply add the scaled ingredient quantity minus the current stock to grocery list
            if(!$groceryListItem){
                $amountToAdd = $ingredientQuantity - $ingredient->getFood()->getStock();

                // If item is not overstocked (if quantity to add is more than the quantity in stock)
                if($amountToAdd > 0){
                    $newGroceryListItemData = array(
                        'foodItemId' => $ingredient->getFood()->getId(),
                        'amount' => $amount
                    );
                    $groceryListItem = $this->groceryListItemFactory->make($newGroceryListItemData);

                    if(!$this->groceryListItemRepository->save($groceryListItem)){
                        throw new \Exception("Unable to add '{$ingredient->getFood()->getName()}' to grocery list", 1);
                    };
                }
            }
            // Otherwise, get new grocery list quantity
            else {
                $currentAmountInGroceryList = $this->groceryListItemRepository->findByFoodId($ingredient->getFood()->getId())->getAmount();

                // Get item's calculated qty to purchase, BEFORE meal is added
                $amountToAddToGroceryListBeforeMeal = $this->groceryListItemRepository->qtyForGroceryList($ingredient->getFood());

                // Calculate amount added by the $user
                $amountAddedByUser = $currentAmountInGroceryList - $amountToAddToGroceryListBeforeMeal;

                // Get item's calculated qty to purchase, AFTER meal is added
                $newGroceryListAmount = $amountToAddToGroceryListBeforeMeal + $ingredientQuantity + $amountAddedByUser;

                $groceryListItem->setAmount($newGroceryListAmount);

                if(!$this->groceryListItemRepository->save($groceryListItem)){
                    $user = $this->session->get('user');
                    $this->log->add($user->getId(), 'Error', 'Save Meal - Unable to update grocery list');
                    throw new \Exception("Unable to update '{$ingredient->getFood()->getName()}' in grocery list", 2);
                }
            }
        }
        if(!$this->mealRepository->save($meal)){
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Meal Save - Unable to save');
            throw new \Exception("Unable to save meal", 3);
        }
    }

    /**
     * Checks whether user has recipes in current household
     * @param Household $household Household to check
     */
    private function checkHasRecipes($household):void {
        if($this->recipeRepository->countForHousehold($household) == 0){
            $this->session->flashMessage('warning',
                "You must add a recipe before scheduling a meal.");
            Redirect::toControllerMethod('Recipes', 'create');
            return;
        }
    }

}
