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

    public function __construct($dependencies){
		$this->dbh = $dependencies['dbh'];
		$this->session = $dependencies['session'];
		$this->request = $dependencies['request'];
		$this->log = $dependencies['log'];

        $this->foodItemRepository = $dependencies['foodItemRepository'];
        $this->groceryListItemFactory = $dependencies['groceryListItemFactory'];
        $this->groceryListItemRepository = $dependencies['groceryListItemRepository'];

    }

    /**
     * Lists all grocery list items belonging to a user
     */
    public function index($param = ''):void {

        $household = $this->session->get('user')->getCurrHousehold();
        $foodItemCount = $this->foodItemRepository->countForHousehold($household);
        $groceryListItems = $this->groceryListItemRepository->allForHousehold($household);
        $showLow = FALSE;
        if($param == 'showLow') $showLow = TRUE;

        $this->view('groceryListItem/index', compact('groceryListItems', 'foodItemCount', 'showLow'));
    }

    /**
     * Show page to edit a grocery list item
     * @param string $id Grocery list item's id
     */
    public function edit($id):void{

        // Get groceryListItem details
        $groceryListItem = $this->groceryListItemRepository->find($id);

        if(!$groceryListItem){
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Grocery List - Invalid item');
            $this->session->flashMessage('danger',
                'Uh oh! Something went wrong. The item does not exist.');

            Redirect::toControllerMethod('GroceryListItems', 'index');
        }

        $this->checkGroceryListItemBelongsToHousehold($id);

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
                throw new \Exception("Invalid food item id or doesn't belong to household", 1);
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
            $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to add item. '.$e->getMessage());
            $this->session->flashMessage('danger',
                'Uh oh! Something went wrong. The item was not added to your grocery list.');

            Redirect::toControllerMethod('GroceryListItems', 'create');
        }
        catch (\Error $e){
            // Log error
            $user = $this->session->get('user');
            $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to add item. '.$e->getMessage());
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
            $this->session->flashMessage('danger', 'An error occurred. Grocery list item does not exist');
            Redirect::toControllerMethod('GroceryListItems', 'index');
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
            $this->log->add($user->getId(), 'Error', 'Check Grocery - Item doesn\'t belong to this household ('.$household->getId().')');
            $this->session->flashMessage('danger', 'An error occurred. The grocery list item does not belong to your household.');
            Redirect::toControllerMethod('GroceryListItems', 'index');
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
                ['foodItemId']
            ],
            'regex' => [
                ['amount', $twoSigDigFloatRegex]
            ],
            'min' => [
                ['amount', 0.01]
            ],
            'max' => [
                ['amount', 100000]
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
                ['amount']
            ],
            'regex' => [
                ['amount', $twoSigDigFloatRegex]
            ],
            'min' => [
                ['amount', 0.01]
            ],
            'max' => [
                ['amount', 100000]
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
         //$this->session->flashOldInput($input);

         $currentHousehold = $this->session->get('user')->getCurrHousehold();

         $groceryListItem = $this->groceryListItemRepository->find($id);

         try {
             if(!$groceryListItem){
                 throw new \Exception("Item is invalid/does not exist", 1);
             }
             // Set food stock as grocery list amount + current food stock
             $groceryListFoodItem = $groceryListItem->getFoodItem();
             $groceryListItemAmount = $groceryListItem->getAmount();
             $groceryListFoodItemStock = $groceryListFoodItem->getStock();
             $newStock = $groceryListFoodItemStock + $groceryListItemAmount;
             $groceryListFoodItem->setStock($newStock);

             // Save to DB
             $this->foodItemRepository->save($groceryListFoodItem);

             // IF the save was successful, delete the grocery list item
             $this->delete($id);
         }
         catch (\Exception $e){
           // Log error
             $user = $this->session->get('user');
             $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to purchase item. '. $e->getMessage());
             $this->session->flashMessage('danger',
                 'Uh oh! Something went wrong. The item was not marked as purchased from your grocery list.');
             Redirect::toControllerMethod('GroceryListItems', 'index');
         }
         catch (\Error $e){
             // Log error
             $user = $this->session->get('user');
             $this->log->add($user->getId(), 'Error', 'Grocery List - Unable to purchase item. '.$e->getMessage());
             $this->session->flashMessage('danger',
                 'Uh oh! Something went wrong. The item was not marked as purchased from your grocery list.');
             Redirect::toControllerMethod('GroceryListItems', 'index');
         }

         // Flash success message and flush old input
         $this->session->flashMessage('success', ucfirst($groceryListItem->getFoodItem()->getName()).' has been purchased.');
         $this->session->flushOldInput();

         // Redirect back after updating
         Redirect::toControllerMethod('GroceryListItems', 'index');
         return;
    }

    /**
     * Checks whether user has food items
     * @param Household $household Household to check
     */
    private function checkHasFoodItems($household):void {
        if($this->foodItemRepository->countForHousehold($household) == 0){
            $this->session->flashMessage('warning',
                "You must create a food item to be able to add it to the grocery
                 list.");
            Redirect::toControllerMethod('FoodItems', 'create');
            return;
        }
    }
}
