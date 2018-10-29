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

class Recipes extends Controller {

    private $recipeRepository;
    private $dbh;

    public function __construct()
    {
        parent::__construct(...func_get_args());

        // Set RecipeRepository
        /* TODO Find a way to inject it using the constructor (or other methods)
         * instead of creating it here
         */
        $this->dbh = DatabaseHandler::getInstance();
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB());
        $this->ingredientRepository = new IngredientRepository($this->dbh->getDB());

    }

    public function index(){
        $user = (new Session())->get('user');

        // echo "In ".__CLASS__."@".__FUNCTION__;
        $recipes = $this->recipeRepository->allForUser($user);
        //$recipes = $this->recipeRepository->all();
        $this->view('recipe/index', compact('recipes'));
    }

    public function edit($id){

        // TODO Choose current household, not first one
        $household = (new Session())->get('user')->getHouseholds()[0];
        $db = $this->dbh->getDB();

        $foodItemRepository = new FoodItemRepository($db);
        $unitRepository = new UnitRepository($db);

        // Get user's fooditems and list of units
        $fooditems = $foodItemRepository->allForHousehold($household);
        $units = $unitRepository->all();

        // Get recipe Object
        $recipe = $this->recipeRepository->find($id);

        //Get the ingredients
        $ingredients = $this->ingredientRepository->allForRecipe($id);

        //Add to the recipe object
          for($i = 0;$i<count($ingredients); $i++) {
            $recipe->addIngredient($ingredients[$i]);
        }

        $this->view('recipe/edit', compact('recipe', 'ingredients', 'fooditems', 'units'));
    }

    public function create(){
        $db = $this->dbh->getDB();

        $foodItemRepository = new FoodItemRepository($db);
        $unitRepository = new UnitRepository($db);

        $household = (new Session())->get('user')->getHouseholds()[0];

        // Get user's foodItems and list of units
        $fooditems = $foodItemRepository->allForHousehold($household);
        $units = $unitRepository->all();

        $this->view('recipe/create', compact('fooditems', 'units'));
    }

    public function store(){

        $input = $_POST;

        $db = $this->dbh->getDB();

        $recipeFactory = new RecipeFactory($db);
        $ingredientFactory = new IngredientFactory($db);

        //Use a RecipeFactory to create the Recipe Object:
        $recipe = $recipeFactory->make($input);

        //Save the recipe in the database:
        if ($this->recipeRepository->save($recipe)) {
          // Flash success message
          (new Session())->flashMessage('success', ucfirst($recipe->getName()).' was added to your recipes.');

        for($i=0;$i<count($input['foodid']);$i++){
          //Create the ingredient array:
          $ingredientInput = array("foodid" => $input['foodid'][$i],
                                  "quantity" => $input['quantity'][$i],
                                  "recipeid" => $recipe->getId(),
                                  "unit_id" => $input['unit_id'][$i]);

            //Create the ingredient object:
            $ingredient = $ingredientFactory->make($ingredientInput);

            //Save the ingredient in the database:
            if($this->ingredientRepository->save($ingredient)) {

              //Add the ingredient to the recipe object:
              $recipe->addIngredient($ingredient);

              // Flash success message
              (new Session())->flashMessage('success', ucfirst($ingredient->getFood()->getName()).' was added to your ingredients.');
            }
            else {
              (new Session())->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($ingredient->getFood()->getName()). ' was not added to your ingredients.');
            }
          }
        }
       else {
          (new Session())->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($recipe->getName()). ' was not added to your recipes.');
        }


        // Redirect back after updating
        Redirect::toControllerMethod('Recipes', 'index');
        return;
    }

    public function delete($id){
            $user = (new Session())->get('user');

            //Remove ingredients for this recipe from the ingredients table:

            //Remove the recipe from the recipes table:
            $recipe = $this->recipeRepository->find($id);

            // If recipe doesn't exist, load 404 error page
            if(!$recipe){
                Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
                return;
            }

            // If recipe doesn't belong to user, do not delete, and show error page
            if(!$this->recipeRepository->recipeBelongsToUser($id, $user)){
                Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 403));
                return;
            }

            $this->recipeRepository->remove($id);

            (new Session())->flashMessage('success', $recipe['name'].' was removed from your recipes.');

            // Redirect to list after deleting
            Redirect::toControllerMethod('Recipes', 'index');
            return;

    }

    public function update($id){
        $db = $this->dbh->getDB();

        $ingredientFactory = new IngredientFactory($db);

        $recipe = $this->recipeRepository->find($id);

        $this->checkRecipeBelongsToUser($id);

        $input = $_POST;

        // Find unit and category
        $db = $this->dbh->getDB();

        $recipe->setId($id);
        $recipe->setName($input['name']);
        $recipe->setDescription($input['description']);
        $recipe->setServings($input['servings']);
        $recipe->setSource($input['source']);
        $recipe->setNotes($input['notes']);

        //Save the recipe in the database:
        if ($this->recipeRepository->save($recipe)) {

          // Flash success message
          (new Session())->flashMessage('success', ucfirst($recipe->getName()).' was updated.');

        for($i=0;$i<count($input['ingredientIds']);$i++){
          echo "\nfoodid = " . $input['foodid'][$i] . "\n";
          echo "\nquantity = " . $input['quantity'][$i] . "\n";
          echo "\nunit_id = " . $input['unit_id'][$i] . "\n";

          //Create the ingredient array:
          $ingredientInput = array("foodid" => $input['foodid'][$i],
                                  "quantity" => $input['quantity'][$i],
                                  "recipeid" => $recipe->getId(),
                                  "unit_id" => $input['unit_id'][$i],
                                  "id" => $input['ingredientIds'][$i]);

            //Create the ingredient object:
          //  $ingredient = $ingredientFactory->make($ingredientInput);

            //Save the ingredient in the database:
            /*
            if($this->ingredientRepository->save($ingredient)) {

              //Add the ingredient to the recipe object:
              $recipe->addIngredient($ingredient);

              // Flash success message
              (new Session())->flashMessage('success', ucfirst($ingredient->getFood()->getName()).' was updated.');
            }
            else {
              (new Session())->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($ingredient->getFood()->getName()). ' was not updated.');
            }
            */
          }
        }
        else {
          (new Session())->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($recipe->getName()). ' was not updated.');
        }

        Redirect::toControllerMethod('Recipes', 'index');

        return;
      /*
        $this->recipeRepository->save($recipe);

        // Flash success message
        (new Session())->flashMessage('success', ucfirst($recipe->getName()).' was updated.');

        //Also update the ingredients


        // Redirect back after updating
        echo "\nrecipeid = " . $recipe->getId() . "\n";

      //  Redirect::toControllerMethod('Recipes', 'edit', $recipe->getId());
      Redirect::toControllerMethod('Recipes', 'index');

        return;
        */
    }

    public function checkRecipeBelongsToUser($id){
        $user = (new Session())->get('user');

        // If recipe doesn't belong to user, show forbidden error
        if(!$this->recipeRepository->recipeBelongsToUser($id, $user)){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }
}
