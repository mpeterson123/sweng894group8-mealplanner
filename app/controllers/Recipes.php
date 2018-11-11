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
use Base\Models\Recipe;
use Base\Repositories\RecipeRepository;
use Base\Repositories\IngredientRepository;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;
use Base\Factories\RecipeFactory;
use Base\Factories\IngredientFactory;
use Base\Factories\FoodItemFactory;
use Base\Factories\CategoryFactory;
use Base\Factories\UnitFactory;
use Base\Repositories\CategoryRepository;

class Recipes extends Controller {

    protected $dbh,
        $session,
        $request;

    private $unitRepository,
        $recipeFactory,
        $recipeRepository,
        $foodItemRepository,
        $ingredientFactory,
        $ingredientRepository;

    public function __construct(DatabaseHandler $dbh, Session $session, $request){
		$this->dbh = $dbh;
		$this->session = $session;
		$this->request = $request;

        // TODO Use dependency injection
        $this->recipeFactory = new RecipeFactory();
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB(), $this->recipeFactory);

        $categoryFactory = new CategoryFactory($this->dbh->getDB());
        $categoryRepository = new CategoryRepository($this->dbh->getDB(), $categoryFactory);

        $unitFactory = new UnitFactory($this->dbh->getDB());
        $this->unitRepository = new UnitRepository($this->dbh->getDB(), $unitFactory);

        $foodItemFactory = new FoodItemFactory($categoryRepository, $this->unitRepository);
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $foodItemFactory);

        $this->ingredientFactory = new IngredientFactory($this->foodItemRepository, $this->unitRepository);
        $this->ingredientRepository = new IngredientRepository($this->dbh->getDB(), $this->ingredientFactory);

    }

    public function index(){
        $household = $this->session->get('user')->getHouseholds()[0];

        // echo "In ".__CLASS__."@".__FUNCTION__;
        $recipes = $this->recipeRepository->allForHousehold($household);

        $this->view('recipe/index', compact('recipes'));
    }

    public function edit($id){

        // TODO Choose current household, not first one
        $household = $this->session->get('user')->getHouseholds()[0];

        // Get user's fooditems and list of units
        $foodItems = $this->foodItemRepository->allForHousehold($household);
        $units = $this->unitRepository->all();

        // Get recipe Object
        $recipe = $this->recipeRepository->find($id);

        //Get the ingredients
        $ingredients = $this->ingredientRepository->allForRecipe($id);

        //Add to the recipe object
          for($i = 0;$i<count($ingredients); $i++) {
            $recipe->addIngredient($ingredients[$i]);
        }

        $this->view('recipe/edit', compact('recipe', 'ingredients', 'foodItems', 'units'));
    }

    public function create(){

        $household = $this->session->get('user')->getHouseholds()[0];

        // Get user's foodItems and list of units
        $foodItems = $this->foodItemRepository->allForHousehold($household);
        $units = $this->unitRepository->all();

        $this->view('recipe/create', compact('foodItems', 'units'));
    }

    public function store(){

        $input = $this->request;

        $this->session->flashOldInput($input);

        // Validate input
        $this->validateInput($input, 'create');

        //Use a RecipeFactory to create the Recipe Object:
        $recipe = $this->recipeFactory->make($input);

        //Save the recipe in the database:
        if ($this->recipeRepository->save($recipe)) {
            // Flash success message
            $this->session->flashMessage('success', ucfirst($recipe->getName()).' was added to your recipes.');

            //Add the ingredients
            $this->addIngredients($input, $recipe);
        }
        else {
          $this->session->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($recipe->getName()). ' was not added to your recipes.');
        }

        // Redirect back after updating
        Redirect::toControllerMethod('Recipes', 'index');
        return;
    }

private function addIngredients($in, $rec) {

  if(isset($in['newFoodId'])) {

    for($i=0;$i<count($in['newFoodId']);$i++) {

        //Create the ingredient array:
        $ingredientInput = array("foodId" => $in['newFoodId'][$i],
                              "quantity" => $in['newQuantity'][$i],
                              "recipeId" => $rec->getId(),
                              "unitId" => $in['newUnitId'][$i]);

        //Create the ingredient object:
        $ingredient = $this->ingredientFactory->make($ingredientInput);

        //Save the ingredient in the database:
        if($this->ingredientRepository->save($ingredient)) {

            //Add the ingredient to the recipe object:
            $rec->addIngredient($ingredient);

            // Flash success message
            $this->session->flashMessage('success', ucfirst($ingredient->getFood()->getName()).' was added to your ingredients.');
        }
        else {
          $this->session->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($ingredient->getFood()->getName()). ' was not added to your ingredients.');
        }
      } //end for
    } //end if new items were returned
  }

    public function delete($id){
            $household = $this->session->get('user')->getHouseholds()[0];

            //Remove the recipe from the recipes table:
            $recipe = $this->recipeRepository->find($id);

            // If recipe doesn't exist, load 404 error page
            if(!$recipe){
                Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
                return;
            }

            // If recipe doesn't belong to household, do not delete, and show error page
            if(!$this->recipeRepository->recipeBelongsToHousehold($id, $household)){
                Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 403));
                return;
            }

            if($this->recipeRepository->remove($id))
            {
              $this->session->flashMessage('success', $recipe->getName().' was removed from your recipes.');
            }
            else {
              $this->session->flashMessage('error', 'Sorry, something went wrong. ' . $recipe->getName().' was not removed from your recipes.');
            }



            // Redirect to list after deleting
            Redirect::toControllerMethod('Recipes', 'index');
            return;
    }

    public function update($id){

        $recipe = $this->recipeRepository->find($id);

        $this->checkRecipeBelongsToHousehold($id);

        $input = $this->request;

        $this->validateInput($input, 'edit', [$id]);

        $recipe->setId($id);
        $recipe->setName($input['name']);
        $recipe->setDirections($input['directions']);
        $recipe->setServings($input['servings']);
        $recipe->setSource($input['source']);
        $recipe->setNotes($input['notes']);

        //Save the recipe in the database:
        if ($this->recipeRepository->save($recipe)) {

          //Flash success message
          $this->session->flashMessage('success', ucfirst($recipe->getName()).' was updated.');

          //Update the existing ingredients
          $this->updateIngredients($input, $recipe);

          //Add new ingredients
          $this->addIngredients($input, $recipe);

        }
        else {
          $this->session->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($recipe->getName()). ' was not updated.');
        }

        Redirect::toControllerMethod('Recipes', 'index');

        return;
    }

    private function updateIngredients($in, $rec) {

      //Get the ingredients associated with this recipe from the repository:
      $currentIngreds = $this->ingredientRepository->allForRecipe($rec->getId());

      //If existing ingredients were returned from the view:
      if(isset($in['ingredientIds'])) {

        //Loop through the ingredients currently in the database, if it doesn't exist in the ingredients returned
        //from the view, remove it from the database:
        for($i=0;$i<count($currentIngreds);$i++) {

          $return = in_array($currentIngreds[$i]->getId(), $in['ingredientIds']);

          if($return != TRUE) {

            $this->ingredientRepository->remove($currentIngreds[$i]->getId());
          }

        }

        //Loop through the ingredients returned from the view, update them in the database,
        //and add them to the recipe object:
        for($i=0;$i<count($in['ingredientIds']);$i++){

          //Create the ingredient array:
          $ingredientInput = array("foodId" => $in['foodId'][$i],
                                "quantity" => $in['quantity'][$i],
                                "recipeId" => $rec->getId(),
                                "unitId" => $in['unitId'][$i],
                                "id" => $in['ingredientIds'][$i]);

          //Create the ingredient object:
          $ingredient = $this->ingredientFactory->make($ingredientInput);

          //Save the ingredient in the database:
          if($this->ingredientRepository->save($ingredient)) {

            //Add the ingredient to the recipe object:
            $rec->addIngredient($ingredient);

            // Flash success message
            $this->session->flashMessage('success', ucfirst($ingredient->getFood()->getName()).' was updated.');
          }
          else {
            $this->session->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($ingredient->getFood()->getName()). ' was not updated.');
          }

        }
      } //end if isset($in['ingredientIds'])
      else {
        //No existing ingredienets were returned from the view.
        //If there are ingredients associated with the recipe, they need to be removed:
        if($currentIngreds) {
          for($i=0;$i<count($currentIngreds);$i++) {
            $this->ingredientRepository->remove($currentIngreds[$i]->getId());
          }
        }
      }
    }

    public function checkRecipeBelongsToUser($id){
        $user = $this->session->get('user');

        // If recipe doesn't belong to user, show forbidden error
        if(!$this->recipeRepository->recipeBelongsToUser($id, $user)){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }

    public function checkRecipeBelongsToHousehold($id){
        $household = $this->session->get('user')->getHouseholds()[0];

        // If recipe doesn't belong to household, show forbidden error
        if(!$this->recipeRepository->recipeBelongsToHousehold($id, $household)){
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

        var_dump($input);
        //exit();

        // Validate input
        $validator = new Validator($input);
        $twoSigDigFloatRegex = '/^[0-9]{1,4}(.[0-9]{1,2})?$/';
        $safeStringRegex = '/^[0-9a-z \n\r#\/\(\)-]+$/i';

        $rules = [
            'required' => [
                ['name'],
                ['servings']
            ],

            'optional' => [
              ['newFoodId'],
              ['newQuantity'],
              ['newUnitId'],
              ['recipeId'],
              ['ingredientIds'],
              ['quantity'],
              ['foodId'],
              ['unitId']
            ],

            'integer' => [
                ['newFoodId.*'],
                ['newUnitId.*'],
                ['recipeId'],
                ['foodId.*'],
                ['unitId.*'],
                ['ingredientIds.*']
            ],

            'regex' => [
                ['name', $safeStringRegex],
                ['directions', $safeStringRegex],
                ['servings', $twoSigDigFloatRegex],
                ['source', $safeStringRegex],
                ['notes', $safeStringRegex]

            ],

            'min' => [
              ['newFoodId.*', 1],
              ['newUnitId.*', 1],
              ['newQuantity.*', .05],
              ['quantity.*', .05]
            ]
        ];
        $validator->rules($rules);

        $validator->labels(array(
            'newFoodId.*' => 'FoodItem',
            'foodId.*' => 'FoodItem',
            'newUnitId.*' => 'Unit',
            'unitId.*' => 'Unit',
            'newQuantity.*' => 'Quantity',
            'quantity.*' => 'Quantity'
        ));


        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Recipes', $method, $params);
            return;
        }
    }
}
