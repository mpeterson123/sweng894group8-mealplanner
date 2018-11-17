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
use Base\Helpers\Log;

/**
 * Grocery list items users can add and keep track of
 */
class GroceryListItems extends Controller {

    protected $dbh,
        $session,
        $request,
        $log;

    private $unitRepository,
        $foodItemRepository,
        $groceryListItemRepository,
        $groceryListItemFactory;

    public function __construct(DatabaseHandler $dbh, Session $session, $request){
    		$this->dbh = $dbh;
    		$this->session = $session;
    		$this->request = $request;
        $this->log = new Log($dbh);

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
    public function index():void {

        $household = $this->session->get('user')->getCurrHousehold();
        $foodItemCount = $this->foodItemRepository->countForHousehold($household);
        $groceryListItems = $this->groceryListItemRepository->allForHousehold($household);

        $this->view('groceryListItem/index', compact('groceryListItems', 'foodItemCount'));
    }

    /**
     * Show page to edit a grocery list item
     * @param string $id Grocery list item's id
     */
    public function edit($id):void{

        // Get groceryListItem details
        $groceryListItem = $this->groceryListItemRepository->find($id);

        $this->view('groceryListItem/edit', compact('groceryListItem'));
    }

    /**
     * Show page to create a grocery list item
     */
    public function create():void{
        $currentHousehold = $this->session->get('user')->getCurrHousehold();
        $this->checkHasFoodItems($currentHousehold);
        
        $foodItems = $this
            ->foodItemRepository
            ->itemsAddableToHouseholdGroceryList($currentHousehold);

        $this->view('groceryListItem/create', compact('foodItems'));
    }

    /**
     * Stores a new grocery list item in the DB
     */
    public function store():void {
        $currentHousehold = $this->session->get('user')->getCurrHousehold();
        $this->checkHasFoodItems($currentHousehold);

        $input = $this->request;

        $this->session->flashOldInput($input);

        // Validate input
        $this->validateCreateInput($input, 'create');



        try {
            if(!$this
                ->foodItemRepository
                ->isAddableToHouseholdGroceryList($input['foodItemId'],
                $currentHousehold)) {
                throw new \Exception("Invalid food item id", 1);
            }

            // Make grocery list item
            $groceryListItem = $this->groceryListItemFactory->make($input);

            // Save to DB
            if(!$this->groceryListItemRepository->save($groceryListItem)){
                throw new \Exception("Error adding grocery list item to DB", 1);
            }
        }
        catch (\Exception $e){
            // Log error
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to add item');
            $this->session->flashMessage('danger',
                'Uh oh! Something went wrong. The item was not added to your grocery list.');

            Redirect::toControllerMethod('GroceryListItems', 'create');
        }
        catch (\Error $e){
            // Log error
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to add item');
            $this->session->flashMessage('danger',
                'Uh oh! Something went wrong. The item was not added to your grocery list.');

            Redirect::toControllerMethod('GroceryListItems', 'create');
        }

        // Flash success message and flush old input
        $this->session->flashMessage('success', ucfirst($groceryListItem->getFoodItem()->getName()).' was added to your list.');
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
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Grocery Delete - Item doesn\'t exist');
            Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
            return;
        }

        $this->checkGroceryListItemBelongsToHousehold($id);

        $this->groceryListItemRepository->remove($id);

        $this->session->flashMessage('success', $groceryListItem->getFoodItem()->getName().' was removed from your items.');

        // Redirect to list after deleting
        Redirect::toControllerMethod('GroceryListItems', 'index');
        return;
    }

    /**
     * Updates a grocery list item in the debug
     * @param string $id Grocery list item's id
     */
    public function update($id):void {
        $groceryListItem = $this->groceryListItemRepository->find($id);
        $this->checkGroceryListItemBelongsToHousehold($id);

        $this->validateEditInput($this->request, 'edit', [$id]);

        $groceryListItem->setAmount($this->request['amount']);
        $this->groceryListItemRepository->save($groceryListItem);

        // Flash success message
        $this->session->flashMessage('success', ucfirst($groceryListItem->getFoodItem()->getName()).' amount was updated in your grocery list.');
        $this->session->flushOldInput();

        // Redirect back after updating
        Redirect::toControllerMethod('GroceryListItems', 'edit', array('groceryListItemId' => $groceryListItem->getId()));
        return;
    }

    /**
     * Check if a grocery list items belongs to the current household
     * @param string $groceryListItemId Grocery list item's id
     */
    public function checkGroceryListItemBelongsToHousehold($groceryListItemId):void{
        $household = $this->session->get('user')->getCurrHousehold();

        // If groceryListItem doesn't belong to household, show forbidden error
        if(!$this->groceryListItemRepository->groceryListItemBelongsToHousehold($groceryListItemId, $household)){
            $user = $this->session->get('user');
            $this->log->add($user, 'Error', 'Check Grocery - Item doesn\'t belong to this household ('.$household->getId().')');
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }

    /**
     * Validates grocery list item input from creation form
     * @param array $input      Grocery list item food item and amount
     * @param string $method    Method to redirect to
     * @param array $params     Parameters for the redirection method
     */
    private function validateCreateInput($input, $method, $params = NULL):void{
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            'required' => [
                ['amount'],
                ['foodItemId']
            ],
            'integer' => [
                ['foodItemId'],
            ],
            'regex' => [
                ['amount', $twoSigDigFloatRegex]
            ],
            'min' => [
                ['amount', 0.01],
            ],
            'max' => [
                ['amount', 9999.99]
            ]
        ];
        $validator->rules($rules);
        // Rule to ensure food item is chosen
        $validator->rule('min', 'foodItemId', 1)->message('{field} is required');
        $validator->labels(array(
            'foodItemId' => 'Food Item',
            'amount' => 'Amount'
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

    /**
     * Validates grocery list item input from editing form
     * @param array $input      Grocery list item food item and amount
     * @param string $method    Method to redirect to
     * @param array $params     Parameters for the redirection method
     */
    private function validateEditInput($input, $method, $params = NULL):void{
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            'required' => [
                ['amount'],
            ],
            'regex' => [
                ['amount', $twoSigDigFloatRegex]
            ],
            'min' => [
                ['amount', 0.01],
            ],
            'max' => [
                ['amount', 9999.99]
            ]
        ];
        $validator->rules($rules);

        $validator->labels(array(
            'amount' => 'Amount'
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

    /**
     * Purchase an item on the grocery list, updating the user's stock
     * @param array $input      Grocery list item food item and amount
     * @param string $method    Method to redirect to
     * @param array $params     Parameters for the redirection method
     */
     public function purchase($id):void{
         $this->session->flashOldInput($input);

         $currentHousehold = $this->session->get('user')->getCurrHousehold();

         $groceryListItem = $this->groceryListItemRepository->find($id);

         try {
             // Set food stock as grocery list amount + current food stock
             $groceryListFoodItem = $this->groceryListItem->getFoodItem();
             $groceryListItemAmount = $this->groceryListItem->getAmount();
             $groceryListFoodItemStock = $groceryListFoodItem->getStock();
             $newStock = $groceryListFoodItemStock + $groceryListItemAmount;
             $groceryListFoodItem->setStock($newStock);

             // Save to DB
             $this->foodItemRepository->save($groceryListFoodItem);
         }
         catch (\Exception $e){
             // Log error
             $user = $this->session->get('user');
             $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to purchase item');
             $this->session->flashMessage('danger',
                 'Uh oh! Something went wrong. The item was not purchased from your grocery list.');
             Redirect::toControllerMethod('GroceryListItems', 'index');
         }
         catch (\Error $e){
             // Log error
             $user = $this->session->get('user');
             $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to purchase item');
             $this->session->flashMessage('danger',
                 'Uh oh!! Something went wrong. The item was not purchased from your grocery list.');
             Redirect::toControllerMethod('GroceryListItems', 'index');
         }

         // Flash success message and flush old input
         $this->session->flashMessage('success', ucfirst($groceryListItem->getFoodItem()->getName()).' has been purchased.');
         $this->session->flushOldInput();

         // Redirect back after updating
         Redirect::toControllerMethod('GroceryListItems', 'index');
         return;
    }

    private function checkHasFoodItems($household){
        if($this->foodItemRepository->countForHousehold($household) != 0){
            $this->session->flashMessage('warning',
                "You must create a food item to be able to add it to the grocery
                 list.");
            Redirect::toControllerMethod('FoodItems', 'create');
            return;
        }
    }
}
