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
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;


/**
 * Food items users can add and keep track of
 */
class FoodItems extends Controller {

    protected $dbh,
        $session;

    private $unitRepository,
        $categoryRepository,
        $foodItemRepository,
        $foodItemFactory;

    public function __construct(DatabaseHandler $dbh, Session $session){
		$this->dbh = $dbh;
		$this->session = $session;

        // TODO Use dependency injection
        $categoryFactory = new CategoryFactory($this->dbh->getDB());
        $this->categoryRepository = new CategoryRepository($this->dbh->getDB(), $categoryFactory);

        $unitFactory = new UnitFactory($this->dbh->getDB());
        $this->unitRepository = new UnitRepository($this->dbh->getDB(), $unitFactory);

        $this->foodItemFactory = new FoodItemFactory($this->categoryRepository, $this->unitRepository);
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $this->foodItemFactory);

    }

    /**
     * Lists all food items belonging to a user
     */
    public function index():void{
        // TODO Choose current household, not first one
        $household = $this->session->get('user')->getHouseholds()[0];
        $foods = $this->foodItemRepository->allForHousehold($household);
        $this->view('food/index', compact('foods'));
    }

    /**
     * Lets users edit a food item
     * @param string $id Food item's id
     */
    public function edit($id):void{
        // Get user's categories, and list of units
        $categories = $this->categoryRepository->all();
        $units = $this->unitRepository->all();

        // Get food details
        $food = $this->foodItemRepository->find($id);

        $this->view('food/edit', compact('food', 'categories', 'units'));
    }

    /**
     * Lets users create a food item
     */
    public function create():void{
        // Get user's categories, and list of units
        $categories = $this->categoryRepository->all();
        $units = $this->unitRepository->all();

        $this->view('food/create', compact('categories', 'units'));
    }

    /**
     * Stores a new food item in the DB
     */
    public function store():void{

        $input = $_POST;

        $this->session->flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        // Make food item
        $foodItem = $this->foodItemFactory->make($input);

        // Save to DB
        $this->foodItemRepository->save($foodItem);

        // Flash success message and flush old input
        $this->session->flashMessage('success', ucfirst($foodItem->getName()).' was added to your list.');
        $this->session->flushOldInput();

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

        $this->checkFoodBelongsToHousehold($id);

        $this->foodItemRepository->remove($id);

        $this->session->flashMessage('success', $foodItem->getName().' was removed from your items.');

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
        $this->checkFoodBelongsToHousehold($id);

        $this->validateInput($_POST, 'edit', [$id]);

        $this->foodItemRepository->save($foodItem);

        // Flash success message
        $this->session->flashMessage('success', ucfirst($foodItem->getName()).' was updated.');

        // Redirect back after updating
        Redirect::toControllerMethod('FoodItems', 'edit', array('foodId' => $foodItem->getId()));
        return;
    }

    /**
     * Check if a food items belongs to the current household
     * @param string $foodItemId Food item's id
     */
    public function checkFoodBelongsToHousehold($foodItemId):void{
        $household = $this->session->get('user')->getHouseholds()[0];

        // If food doesn't belong to household, show forbidden error
        if(!$this->foodItemRepository->foodBelongsToHousehold($foodItemId, $household)){
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
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            'required' => [
                ['name'],
                ['categoryId'],
                ['unitId'],
                ['unitsInContainer'],
                ['containerCost'],
                ['stock']
            ],
            'integer' => [
                ['categoryId'],
                ['unitId']
            ],
            'regex' => [
                ['name', $safeStringRegex],
                ['unitsInContainer', $twoSigDigFloatRegex],
                ['containerCost', $twoSigDigFloatRegex],
                ['stock', $twoSigDigFloatRegex]
            ]
        ];
        $validator->rules($rules);
        $validator->labels(array(
            'categoryId' => 'Category',
            'unitId' => 'Unit'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('FoodItems', $method, $params);
            return;
        }
    }
}
