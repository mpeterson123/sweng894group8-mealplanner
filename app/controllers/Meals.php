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
use Base\Factories\MealFactory;

class Meals extends Controller {

    private $mealRepository;
    private $dbh;

    public function __construct()
    {
        parent::__construct(...func_get_args());

        $this->dbh = DatabaseHandler::getInstance();
        $this->mealRepository = new MealRepository($this->dbh->getDB());
    }

    public function index():void{
        // echo "In ".__CLASS__."@".__FUNCTION__;
        $meals = $this->mealRepository->allForUser(Session::get('id'));
        $this->view('meal/index', compact('meals'));
    }

    public function edit($id):void{
        $db = $this->dbh->getDB();

        $meal = $this->mealRepository->find($id);

        $this->view('meal/edit', compact('meal'));
    }

    public function create():void{
        $db = $this->dbh->getDB();

        $this->view('meal/create', compact('meal'));
    }

    public function store():void{

        $input = $_POST;
        Session::flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        // Make meal
        $meal = (new MealFactory($this->dbh->getDB()))->make($input);

        // Save to DB
        $this->mealRepository->save($meal);

        // Flash success message and flush old input
        Session::flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was added to your list.');
        Session::flushOldInput();

        // Redirect back after updating
        Redirect::toControllerMethod('Meal', 'index');
        return;
    }

    public function delete($id):void{
        $meal = $this->mealRepository->find($id);

        // If meal doesn't exist, load 404 error page
        if(!$meal){
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }

        $this->checkMealBelongsToUser($id);
        $this->mealRepository->remove($id);

        Session::flashMessage('success: meal with date of ', $meal->getDate().' was removed.');

        // Redirect to list after deleting
        Redirect::toControllerMethod('Meal', 'index');
        return;
    }

    public function update($id):void{
        $meal = $this->mealRepository->find($id);
        $this->checkMealBelongsToUser($id);

        $this->validateInput($_POST, 'edit', [$id]);

        $this->mealRepository->save($meal);

        // Flash success message
        Session::flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was updated.');

        // Redirect back after updating
        Redirect::toControllerMethod('Meal', 'edit', array('Meal' => $meal->getId()));
        return;
    }

    public function checkMealBelongsToUser($id):void{
        // If meal doesn't belong to user, show forbidden error
        if(!$this->mealRepository->mealBelongsToUser($id, Session::get('id'))){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }

    private function validateInput($input, $method, $params = NULL):void{
        Session::flashOldInput($input);

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
            Session::flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Meal', $method, $params);
            return;
        }
    }
}
