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
use Base\Models\GroceryListItem;
use Base\Repositories\GroceryListItemRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\CategoryRepository;
use Base\Repositories\FoodItemRepository;
use Base\Factories\GroceryListItemFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\UnitFactory;
use Base\Factories\CategoryFactory;


/**
 * Grocery list items users can add and keep track of
 */
class GroceryListItems extends Controller {

    protected $dbh,
        $session,
        $request;

    private $unitRepository,
        $foodItemRepository,
        $groceryListItemRepository,
        $groceryListItemFactory;

    public function __construct(DatabaseHandler $dbh, Session $session, $request){
		$this->dbh = $dbh;
		$this->session = $session;
		$this->request = $request;

        // TODO Use dependency injection
        $categoryFactory = new CategoryFactory($this->dbh->getDB());
        $categoryRepository = new CategoryRepository($this->dbh->getDB(), $categoryFactory);

        $unitFactory = new UnitFactory($this->dbh->getDB());
        $unitRepository = new UnitRepository($this->dbh->getDB(), $unitFactory);

        $foodItemFactory = new FoodItemFactory($categoryRepository, $unitRepository);
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $foodItemFactory);

        $this->groceryListItemFactory = new GroceryListItemFactory($this->foodItemRepository);
        $this->groceryListItemRepository = new GroceryListItemRepository($this->dbh->getDB(), $this->groceryListItemFactory);

    }

    /**
     * Lists all grocery list items belonging to a user
     */
    public function index():void{
        // TODO Choose current household, not first one
        $household = $this->session->get('user')->getCurrHousehold();
        $groceryListItems = $this->groceryListItemRepository->allForHousehold($household);
        $this->view('groceryListItem/index', compact('groceryListItems'));
    }

    /**
     * Lets users edit a grocery list item
     * @param string $id Grocery list item's id
     */
    public function edit($id):void{
        // Get user's categories, and list of units
        $categories = $this->foodItemRepository->all();
        $units = $this->unitRepository->all();

        // Get groceryListItem details
        $groceryListItem = $this->groceryListItemRepository->find($id);

        $this->view('groceryListItem/edit', compact('groceryListItem', 'categories', 'units'));
    }

    /**
     * Lets users create a grocery list item
     */
    public function create():void{
        // Get user's categories, and list of units
        $categories = $this->foodItemRepository->all();
        $units = $this->unitRepository->all();

        $this->view('groceryListItem/create', compact('categories', 'units'));
    }

    /**
     * Stores a new grocery list item in the DB
     */
    public function store():void{

        $input = $this->request;

        $this->session->flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        // Make grocery list item
        $groceryListItem = $this->groceryListItemFactory->make($input);

        // Save to DB
        $this->groceryListItemRepository->save($groceryListItem);

        // Flash success message and flush old input
        $this->session->flashMessage('success', ucfirst($groceryListItem->getName()).' was added to your list.');
        $this->session->flushOldInput();

        // Redirect back after updating
        Redirect::toControllerMethod('GroceryListItems', 'index');
        return;
    }

    /**
     * Deletes a grocery list item
     * @param string $id Grocery list item's id
     */
    public function delete($id):void{
        $groceryListItem = $this->groceryListItemRepository->find($id);

        // If groceryListItem doesn't exist, load 404 error page
        if(!$groceryListItem){
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }

        $this->checkGroceryListItemBelongsToHousehold($id);

        $this->groceryListItemRepository->remove($id);

        $this->session->flashMessage('success', $groceryListItem->getName().' was removed from your items.');

        // Redirect to list after deleting
        Redirect::toControllerMethod('GroceryListItems', 'index');
        return;
    }

    /**
     * Updates a grocery list item in the debug
     * @param string $id Grocery list item's id
     */
    public function update($id):void{
        $groceryListItem = $this->groceryListItemRepository->find($id);
        $this->checkGroceryListItemBelongsToHousehold($id);

        $this->validateInput($this->request, 'edit', [$id]);

        $this->groceryListItemRepository->save($groceryListItem);

        // Flash success message
        $this->session->flashMessage('success', ucfirst($groceryListItem->getName()).' was updated.');

        // Redirect back after updating
        Redirect::toControllerMethod('GroceryListItems', 'edit', array('groceryListItemId' => $groceryListItem->getId()));
        return;
    }

    /**
     * Check if a grocery list items belongs to the current household
     * @param string $groceryListItemId Grocery list item's id
     */
    public function checkGroceryListItemBelongsToHousehold($groceryListItemId):void{
        $household = $this->session->get('user')->getHouseholds()[0];

        // If groceryListItem doesn't belong to household, show forbidden error
        if(!$this->groceryListItemRepository->groceryListItemBelongsToHousehold($groceryListItemId, $household)){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }

    /**
     * Validates grocery list item input from user form
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
                ['foodItemId'],
                ['unitId'],
                ['unitsInContainer'],
                ['containerCost'],
                ['stock']
            ],
            'integer' => [
                ['foodItemId'],
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
            'foodItemId' => 'FoodItem',
            'unitId' => 'Unit'
        ));

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('GroceryListItems', $method, $params);
            return;
        }
    }
}
