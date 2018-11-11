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
use Base\Factories\MealFactory;
use Base\Factories\RecipeFactory;

class Meals extends Controller {

    protected $dbh,
        $session,
        $request;

    private $mealRepository,
        $mealFactory,
        $recipeRepository;

    public function __construct(DatabaseHandler $dbh, Session $session, $request){
		$this->dbh = $dbh;
		$this->session = $session;
		$this->request = $request;

        // TODO Use dependency injection
        $recipeFactory = new RecipeFactory();
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB(), $recipeFactory);
        $this->mealFactory = new MealFactory($this->recipeRepository);
        $this->mealRepository = new MealRepository($this->dbh->getDB(), $this->mealFactory);

    }

    public function index():void{
        $household = $this->session->get('user')->getCurrHousehold();
        $meals = $this->mealRepository->allForHousehold($household);

        $this->view('meal/index', compact('meals'));
    }

    /**
     * Show page for editing an existing meal
     * @param integer $id Meal id
     */
    public function edit($id):void{
        $db = $this->dbh->getDB();

        $meal = $this->mealRepository->find($id);

        $this->view('meal/edit', compact('meal'));
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


        $this->checkMealBelongsToHousehold($id);
        $this->mealRepository->remove($meal);

        $this->session->flashMessage('success: meal with date of ', $meal->getDate().' was removed.');

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

        if( $this->checkMealBelongsToHousehold($id) )
        {

          $this->validateEditInput($this->request, 'edit', [$id]);

          $this->mealRepository->save($meal);

          // Flash success message
          $this->session->flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was updated.');

          // Redirect back after updating
          Redirect::toControllerMethod('Meals', 'edit', array('Meals' => $meal->getId()));
          return;
        }
        // TODO Decide what to do here
        else
        {
          //not in household
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
            'timestamp' => [
               ['date'],
               ['addedDate']
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
}
