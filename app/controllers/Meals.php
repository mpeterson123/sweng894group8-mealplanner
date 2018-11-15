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
use Base\Repositories\FoodItemRepository;
use Base\Repositories\CategoryRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\IngredientRepository;
use Base\Factories\MealFactory;
use Base\Factories\RecipeFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;
use Base\Factories\IngredientFactory;

class Meals extends Controller {

        protected $dbh,
            $session,
            $request;

        private $MealRepository,
        $RecipeRepository,
        $FoodItemRepository,
        $CategoryRepository,
        $UnitRepository,
        $MealFactory,
        $RecipeFactory,
        $FoodItemFactory,
        $CategoryFactory,
        $UnitFactory;

        public function __construct(DatabaseHandler $dbh, Session $session, $request){
    		$this->dbh = $dbh;
    		$this->session = $session;
    		$this->request = $request;

        // TODO Use dependency injection
        $this->categoryFactory = new CategoryFactory($this->dbh->getDB());
        $this->categoryRepository = new CategoryRepository($this->dbh->getDB(), $this->categoryFactory);
        $this->unitFactory = new UnitFactory($this->dbh->getDB());
        $this->unitRepository = new UnitRepository($this->dbh->getDB(), $this->unitFactory);
        $this->foodItemFactory = new FoodItemFactory($this->categoryRepository, $this->unitRepository);
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $this->foodItemFactory);
        $ingredientFactory = new IngredientFactory($this->foodItemRepository, $this->unitRepository);
        $ingredientRepository = new IngredientRepository($this->dbh->getDB(), $ingredientFactory);
        $this->recipeFactory = new RecipeFactory($ingredientRepository);
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB(), $this->recipeFactory);
        $this->mealFactory = new MealFactory($this->recipeRepository);
        $this->mealRepository = new MealRepository($this->dbh->getDB(), $this->mealFactory);


    }

    public function index():void{
        $household = $this->session->get('user')->getCurrHousehold();
        $meals = $this->mealRepository->incompleteForHousehold($household);

        // Need to figure out how to toggle between entire list and not completed list
        // $meals = $this->mealRepository->allForHousehold($household);

        $this->view('meal/index', compact('meals'));
    }

    /**
     * Show page for editing an existing meal
     * @param integer $id Meal id
     */
    public function edit($id):void{

        // Get all recipes in household, for edit dropdown recipe selection
        $db = $this->dbh->getDB();
        $household = $this->session->get('user')->getCurrHousehold();
        $recipes = $this->recipeRepository->allForHousehold($household);

        // Get meal by ID
        $meal = $this->mealRepository->find($id);

        $this->view('meal/edit', compact('meal','recipes'));
    }

    /**
     * Show page for scheduling a new meal
     */
    public function create():void{
        $db = $this->dbh->getDB();

        $household = $this->session->get('user')->getCurrHousehold();
        $recipes = $this->recipeRepository->allForHousehold($household);

        $this->view('meal/create', compact('recipes'));

    }

    /**
     * Save a new meal to the DB
     */
    public function store():void{

        $input = $this->request;
        $this->session->flashOldInput($input);
        $currentHousehold = $this->session->get('user')->getCurrHousehold();

        // Validate input
        $this->validateCreateInput($input, 'create');

        // Check if recipe belongs to the user's household
        if(!$this->recipeRepository->recipeBelongsToHousehold($input['recipeId'], $currentHousehold)) {
            $this->session->flashMessage('danger', 'Uh oh. The recipe you selected does not belong to your household.');
            Redirect::toControllerMethod('Meals', 'create');
        };

        // Change date to correct format
        $input['date'] = Format::date($input['date']);

        // Make meal
        $meal = $this->mealFactory->make($input);

        // Save to DB
        if(!$this->mealRepository->save($meal)){
            $this->session->flashMessage('danger', 'Uh oh, something went wrong. Your meal could not be saved.');
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

        // If meal doesn't exist, load 404 error page
        if(!$meal){
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

        $meal = $this->mealRepository->find($id);

        $currentHousehold = $this->session->get('user')->getCurrHousehold();

        if( $this->mealRepository->mealBelongsToHousehold($id,$currentHousehold->getId()))
        {
          $input = $this->request;
          $this->validateEditInput($input, 'edit', [$id]);

          $meal->setId($id);
          $meal->setScaleFactor($input['scale']);
          $meal->setDate($input['date']);
          $meal->setRecipe($input['recipe']);

          $this->mealRepository->save($meal);

          // Flash success message
          $this->session->flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was updated.');

          // Redirect back after updating
          Redirect::toControllerMethod('Meals', 'edit', array('Meals' => $meal->getId()));
          return;
        }
        else
        {
          //not in household
          $this->session->flashMessage('danger', 'Uh oh. The meal you selected does not belong to your household.');
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
                ['recipe'],
                ['date'],
                ['isComplete'],
                ['addedDate'],
                ['scaleFactor']
            ],
            'boolean' => [
                ['isComplete']
            ],
            //'timestamp' => [
            //   ['date'],
            //   ['addedDate']
            //],
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
    public function complete($id):void{
        $meal = $this->mealRepository->find($id);
        $currentHousehold = $this->session->get('user')->getCurrHousehold();

        if( $this->mealRepository->mealBelongsToHousehold($id,$currentHousehold->getId()))
        {
          // Update Meal
          $meal->complete();
          $this->mealRepository->save($meal);

          // Update Food items from ingredients from recipe from meal
          $ingredientList = $meal->getRecipe()->getIngredients();
          for($i=0;$i<count($ingredientList);$i++){
      			$foodItem = $ingredientList[$i]->getFood();
      			$this->foodItemRepository->save($foodItem);
      		}

          // Flash success message
          $this->session->flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was completed.');

          // Redirect back with errors
          Redirect::toControllerMethod('Meals', $method, $params);
          return;
        }
        else
        {
          //not in household
          $this->session->flashMessage('error: meal not in household.');
        }
    }
}
