<?php
namespace Base\Controllers;
////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once __DIR__.'/../core/Controller.php';
require_once __DIR__.'/../core/DatabaseHandler.php';
require_once __DIR__.'/../repositories/FoodItemRepository.php';
require_once __DIR__.'/../repositories/UnitRepository.php';
require_once __DIR__.'/../repositories/CategoryRepository.php';



/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Repositories\FoodItemRepository;
use Base\Repositories\UnitRepository;
use Base\Repositories\CategoryRepository;



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

    public function index(){
        // session_start();
        // echo "In ".__CLASS__."@".__FUNCTION__;
        $foods = $this->foodItemRepository->allForUser($_SESSION['id']);
        $this->view('food/index', compact('foods'));
    }

    public function edit($id){
        $db = $this->dbh->getDB();
        $categoryRepository = new CategoryRepository($db);
        $unitRepository = new UnitRepository($db);

        // Get food details
        $food = $this->foodItemRepository->find($id);

        // Get user's categories
        $categories = $categoryRepository->allForUser($_SESSION['id']);
        $units = $unitRepository->all();

        $this->view('food/edit', compact('food', 'categories', 'units'));
    }
}
