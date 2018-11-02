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
        $session;

    private $ingredientRepository,
        $recipeRepository,
        $foodItemRepository,
        $unitRepository;

    public function __construct(DatabaseHandler $dbh, Session $session){
		    $this->dbh = $dbh;
		      $this->session = $session;

        // TODO Use dependency injection
        $this->recipeRepository = new RecipeRepository($this->dbh->getDB());
        $this->ingredientRepository = new IngredientRepository($this->dbh->getDB());

        $categoryFactory = new CategoryFactory($this->dbh->getDB());
        $categoryRepository = new CategoryRepository($this->dbh->getDB(), $categoryFactory);

        $unitFactory = new UnitFactory($this->dbh->getDB());
        $this->unitRepository = new UnitRepository($this->dbh->getDB(), $unitFactory);

        $foodItemFactory = new FoodItemFactory($categoryRepository, $this->unitRepository);
        $this->foodItemRepository = new FoodItemRepository($this->dbh->getDB(), $foodItemFactory);
        $this->ingredientFactory = new IngredientFactory($this->dbh->getDB(), $this->foodItemRepository, $this->unitRepository);
    }

    public function index(){
        $user = $this->session->get('user');

        // echo "In ".__CLASS__."@".__FUNCTION__;
        $recipes = $this->recipeRepository->allForUser($user);

        $this->view('recipe/index', compact('recipes'));
    }

    public function edit($id){

        // TODO Choose current household, not first one
        $household = $this->session->get('user')->getHouseholds()[0];
        $db = $this->dbh->getDB();

        // Get user's fooditems and list of units
        $fooditems = $this->foodItemRepository->allForHousehold($household);
        $units = $this->unitRepository->all();

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

        $household = $this->session->get('user')->getHouseholds()[0];

        // Get user's foodItems and list of units
        $fooditems = $this->foodItemRepository->allForHousehold($household);
        $units = $this->unitRepository->all();

        $this->view('recipe/create', compact('fooditems', 'units'));
    }

    public function store(){

        $input = $_POST;

        $db = $this->dbh->getDB();

        $recipeFactory = new RecipeFactory($db);

        //Use a RecipeFactory to create the Recipe Object:
        $recipe = $recipeFactory->make($input);

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

  $db = $this->dbh->getDB();

  $ingredientFactory = new IngredientFactory($db);

  for($i=0;$i<count($in['newFoodId']);$i++){

      //Create the ingredient array:
      $ingredientInput = array("foodid" => $in['newFoodId'][$i],
                            "quantity" => $in['newQuantity'][$i],
                            "recipeid" => $rec->getId(),
                            "unit_id" => $in['newUnitId'][$i]);

      //Create the ingredient object:
      $ingredient = $ingredientFactory->make($ingredientInput);

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
  }
  return;
}

    public function delete($id){
            $user = $this->session->get('user');

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

            $this->session->flashMessage('success', $recipe->getName().' was removed from your recipes.');

            // Redirect to list after deleting
            Redirect::toControllerMethod('Recipes', 'index');
            return;

    }

    public function update($id){
        $db = $this->dbh->getDB();

        $recipe = $this->recipeRepository->find($id);

        $this->checkRecipeBelongsToUser($id);

        $input = $_POST;

        $recipe->setId($id);
        $recipe->setName($input['name']);
        $recipe->setDescription($input['description']);
        $recipe->setServings($input['servings']);
        $recipe->setSource($input['source']);
        $recipe->setNotes($input['notes']);

        //Save the recipe in the database:
        if ($this->recipeRepository->save($recipe)) {

          // Flash success message
          $this->session->flashMessage('success', ucfirst($recipe->getName()).' was updated.');

          //Update the existing ingredients
          $this->updateIngredients($input, $recipe);

          $this->addIngredients($input, $recipe);

        }
        else {
          $this->session->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($recipe->getName()). ' was not updated.');
        }

        Redirect::toControllerMethod('Recipes', 'index');

        return;
    }

    private function updateIngredients($in, $rec) {

      $db = $this->dbh->getDB();

      $ingredientFactory = new IngredientFactory($db);

      for($i=0;$i<count($in['ingredientIds']);$i++){

        //Create the ingredient array:
        $ingredientInput = array("foodid" => $in['foodId'][$i],
                              "quantity" => $in['quantity'][$i],
                              "recipeid" => $rec->getId(),
                              "unit_id" => $in['unitId'][$i],
                              "id" => $in['ingredientIds'][$i]);

        //Create the ingredient object:
        $ingredient = $ingredientFactory->make($ingredientInput);

        //Save the ingredient in the database:
        if($this->ingredientRepository->save($ingredient)) {

          //Add the ingredient to the recipe object:
          $rec->addIngredient($ingredient);

          // Flash success message
          //$this->session->flashMessage('success', ucfirst($ingredient->getFood()->getName()).' was updated.');
        }
        else {
          $this->session->flashMessage('error', 'Sorry, something went wrong. ' . ucfirst($ingredient->getFood()->getName()). ' was not updated.');
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
}
