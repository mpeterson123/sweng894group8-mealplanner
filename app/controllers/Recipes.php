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
    }

    public function index(){
        // echo "In ".__CLASS__."@".__FUNCTION__;
        $recipes = $this->recipeRepository->allForUser(Session::get('id'));
        //$recipes = $this->recipeRepository->all();
        $this->view('recipe/index', compact('recipes'));
    }

    public function edit($id){
        $db = $this->dbh->getDB();
      //  $categoryRepository = new CategoryRepository($db);
        //$unitRepository = new UnitRepository($db);

        // Get user's categories, and list of units
      //  $categories = $categoryRepository->all();
      //  $units = $unitRepository->all();

        // Get food details
        $recipe = $this->recipeRepository->find($id);

        $this->view('recipe/edit', compact('recipe'));//, 'categories', 'units'));
    }

    public function create(){
        $db = $this->dbh->getDB();
      //  $categoryRepository = new CategoryRepository($db);
      //  $unitRepository = new UnitRepository($db);

        // Get user's categories, and list of units
      //  $categories = $categoryRepository->all();
      //  $units = $unitRepository->all();

        $this->view('recipe/create');//, compact('categories', 'units'));
    }

    public function store(){

        $input = $_POST;

        // Find unit and category
        $db = $this->dbh->getDB();
      //  $categoryRepository = new CategoryRepository($db);
        //$unitRepository = new UnitRepository($db);

        // Get user's categories
        //$category = $categoryRepository->find($input['category_id']);
      //  $unit = $unitRepository->find($input['unit_id']);

        $recipe = new Recipe($input['name'],$input['description'],$input['servings'], $input['source'], $input['notes']);
        //$recipe->setName($input['name']);
        //$recipe->setStock($input['stock']);

        $this->recipeRepository->save($recipe);

        // Flash success message
        Session::flashMessage('success', ucfirst($recipe->getName()).' was added to your list.');

        // Redirect back after updating
        Redirect::toControllerMethod('Recipes', 'index');
        return;
    }

    public function delete($id){
            $recipe = $this->recipeRepository->find($id);

            // If food doesn't exist, load 404 error page
            if(!$recipe){
                Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 404));
                return;
            }

            // If recipe doesn't belong to user, do not delete, and show error page
            if(!$this->recipeRepository->recipeBelongsToUser($id, Session::get('id'))){
                Redirect::toControllerMethod('Errors', 'show', array('errorCode' => 403));
                return;
            }

            $this->recipeRepository->remove($id);

            Session::flashMessage('success', $recipe['name'].' was removed from your recipes.');

            // Redirect to list after deleting
            Redirect::toControllerMethod('Recipes', 'index');
            return;

    }

    public function update($id){
        $recipe = $this->recipeRepository->find($id);

        $this->checkRecipeBelongsToUser($id);

        $input = $_POST;

        // Find unit and category
        $db = $this->dbh->getDB();
        //$categoryRepository = new CategoryRepository($db);
        //$unitRepository = new UnitRepository($db);

        // Get user's categories
        //$category = $categoryRepository->find($input['category_id']);
        //$unit = $unitRepository->find($input['unit_id']);

        $recipe = new Recipe();
        $recipe->setId($id);
        $recipe->setName($input['name']);
        $recipe->setDescription($input['description']);
        $recipe->setServings($input['servings']);
        $recipe->setSource($input['source']);
        $recipe->setNotes($input['notes']);

        $this->recipeRepository->save($recipe);

        // Flash success message
        Session::flashMessage('success', ucfirst($recipe->getName()).' was updated.');

        // Redirect back after updating
        //Redirect::toControllerMethod('Recipes', 'edit', array('recipeId' => $food->getId()));
        Redirect::toControllerMethod('Recipes', 'edit', $recipe->getId());
        return;
    }

    public function checkRecipeBelongsToUser($id){
        // If recipe doesn't belong to user, show forbidden error
        if(!$this->recipeRepository->recipeBelongsToUser($id, Session::get('id'))){
            Redirect::toControllerMethod('Errors', 'show', array('errrorCode', '403'));
            return;
        }
    }
}
