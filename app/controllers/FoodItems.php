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
use Base\Models\FoodItem;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\CategoryRepository;
use Base\Factories\FoodItemFactory;


/**
 * Food items users can add and keep track of
 */
class FoodItems extends Controller {

    private $foodItemRepository;
    private $dbh;

    public function __construct()
    {
        parent::__construct(...func_get_args());

        // Set FoodItemRepository
        /* TODO Find a way to inject it using the constructor (or other methods)
         * instead of creating it here
         */
        $this->dbh = DatabaseHandler::getInstance();
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB());
    }

    /**
     * Lists all food items belonging to a user
     */
    public function index():void{
        // echo "In ".__CLASS__."@".__FUNCTION__;
        $foods = $this->foodItemRepository->allForUser(Session::get('id'));
        $this->view('food/index', compact('foods'));
    }

    /**
     * Lets users edit a food item
     * @param string $id Food item's id
     */
    public function edit($id):void{
        $db = $this->dbh->getDB();
        $categoryRepository = new CategoryRepository($db);
        $unitRepository = new UnitRepository($db);

        // Get user's categories, and list of units
        $categories = $categoryRepository->all();
        $units = $unitRepository->all();

        // Get food details
        $food = $this->foodItemRepository->find($id);

        $this->view('food/edit', compact('food', 'categories', 'units'));
    }

    /**
     * Lets users create a food item
     */
    public function create():void{
        $db = $this->dbh->getDB();
        $categoryRepository = new CategoryRepository($db);
        $unitRepository = new UnitRepository($db);

        // Get user's categories, and list of units
        $categories = $categoryRepository->all();
        $units = $unitRepository->all();

        $this->view('food/create', compact('categories', 'units'));
    }

    /**
     * Stores a new food item in the DB
     */
    public function store():void{

        $input = $_POST;

        Session::flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        // Make food item
        $foodItem = (new FoodItemFactory($this->dbh->getDB()))->make($input);

        // Save to DB
        $this->foodItemRepository->save($foodItem);

        // Flash success message and flush old input
        Session::flashMessage('success', ucfirst($foodItem->getName()).' was added to your list.');
        Session::flushOldInput();

        // Redirect back after updating
        Redirect::toControllerMethod('FoodItems', 'index');
        return;
    }

    /**
     * Deletes a food item
     * @param string $id Food item's id
     */
    public function delete($id):void{
        $foodItem = $this->foodItemRepository->find($id);

        // If food doesn't exist, load 404 error page
        if(!$foodItem){
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }

        $this->checkFoodBelongsToUser($id);

        $this->foodItemRepository->remove($id);

        Session::flashMessage('success', $foodItem->getName().' was removed from your items.');

        // Redirect to list after deleting
        Redirect::toControllerMethod('FoodItems', 'index');
        return;
    }

    /**
     * Updates a food item in the debug
     * @param string $id Food item's id
     */
    public function update($id):void{
        $foodItem = $this->foodItemRepository->find($id);
        $this->checkFoodBelongsToUser($id);

        $this->validateInput($_POST, 'edit', [$id]);

        $this->foodItemRepository->save($foodItem);

        // Flash success message
        Session::flashMessage('success', ucfirst($food->getName()).' was updated.');

        // Redirect back after updating
        Redirect::toControllerMethod('FoodItems', 'edit', array('foodId' => $food->getId()));
        return;
    }

    /**
     * Check if a food items belongs to the current user
     * @param string $id Food item's id
     */
    public function checkFoodBelongsToUser($id):void{
        // If food doesn't belong to user, show forbidden error
        if(!$this->foodItemRepository->foodBelongsToUser($id, Session::get('id'))){
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
        Session::flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            'required' => [
                ['name'],
                ['category_id'],
                ['unit_id'],
                ['units_in_container'],
                ['container_cost'],
                ['stock']
            ],
            'integer' => [
                ['category_id'],
                ['unit_id']
            ],
            'regex' => [
                ['name', $safeStringRegex],
                ['units_in_container', $twoSigDigFloatRegex],
                ['container_cost', $twoSigDigFloatRegex],
                ['stock', $twoSigDigFloatRegex]
            ]
        ];
        $validator->rules($rules);
        $validator->labels(array(
            'category_id' => 'Category',
            'unit_id' => 'Unit'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            Session::flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('FoodItems', $method, $params);
            return;
        }
    }
}
