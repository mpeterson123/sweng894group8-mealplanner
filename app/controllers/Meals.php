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

    protected $dbh,
        $session;

    private $mealRepository,
        $mealFactory;

    public function __construct(DatabaseHandler $dbh, Session $session){
		$this->dbh = $dbh;
		$this->session = $session;

        // TODO Use dependecy injection
        $this->mealRepository = new MealRepository($this->dbh->getDB());
        $this->mealFactory = new MealFactory($this->dbh->getDB());
    }

    public function index():void{
        $user = $this->session->get('user');

        $meals = $this->mealRepository->allForUser($user);
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
        $this->session->flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        // Make meal
        $meal = $this->mealFactory->make($input);

        // Save to DB
        $this->mealRepository->save($meal);

        // Flash success message and flush old input
        $this->session->flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was added to your list.');
        $this->session->flushOldInput();

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

        $this->session->flashMessage('success: meal with date of ', $meal->getDate().' was removed.');

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
        $this->session->flashMessage('success: meal with date of ', ucfirst($meal->getDate()).' was updated.');

        // Redirect back after updating
        Redirect::toControllerMethod('Meal', 'edit', array('Meal' => $meal->getId()));
        return;
    }

    public function checkMealBelongsToUser($id):void{
        $user = $this->session->get('user');

        // If meal doesn't belong to user, show forbidden error
        if(!$this->mealRepository->mealBelongsToUser($id, $user)){
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => '403'));
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
            Redirect::toControllerMethod('Meal', $method, $params);
            return;
        }
    }
}
