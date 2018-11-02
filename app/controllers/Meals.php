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
        $session;

    private $mealRepository,
        $mealFactory,
        $recipeRepository;

    public function __construct(DatabaseHandler $dbh, Session $session){
		$this->dbh = $dbh;
		$this->session = $session;

        // TODO Use dependency injection
        $recipeFactory = new RecipeFactory($this->dbh->getDB());
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB(), $recipeFactory);
        $this->mealRepository = new MealRepository($this->dbh->getDB());
        $this->mealFactory = new MealFactory($this->recipeRepository);

    }

    public function index():void{
        $user = $this->session->get('user');

        $meals = $this->mealRepository->allForHousehold($user->getHouseholds()[0]);
        $this->view('meal/index', compact('meals'));
    }

    public function edit($id):void{
        $db = $this->dbh->getDB();

        $meal = $this->mealRepository->find($id);

        $this->view('meal/edit', compact('meal'));
    }

    public function create():void{
        $db = $this->dbh->getDB();

        $household = $this->session->get('user')->getHouseholds()[0];
        $recipes = $this->recipeRepository->allForHousehold($household);

        $this->view('meal/create', compact('recipes'));

    }

    public function store():void{

        $input = $_POST;
        $this->session->flashOldInput($input);

        // Validate input
        $this->validateCreateInput($input, 'create');

        $input['recipe'] = $input['recipeid'];

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

    public function delete($id):void{
        $meal = $this->mealRepository->find($id);

        // If meal doesn't exist, load 404 error page
        if(!$meal){
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }


        $this->checkMealBelongsToHousehold($id);
        $this->mealRepository->remove($id);

        $this->session->flashMessage('success: meal with date of ', $meal->getDate().' was removed.');

        // Redirect to list after deleting
        Redirect::toControllerMethod('Meals', 'index');
        return;
    }

    public function update($id):void{
        $meal = $this->mealRepository->find($id);

        if( $this->checkMealBelongsToHousehold($id) )
        {

          $this->validateInput($_POST, 'edit', [$id]);

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
        }
    }

    private function validateCreateInput($input, $method, $params = NULL):void{
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $rules = [
            'required' => [
                ['recipeid'],
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

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Meals', $method, $params);
            return;
        }
    }

    private function validateInput($input, $method, $params = NULL):void{
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
