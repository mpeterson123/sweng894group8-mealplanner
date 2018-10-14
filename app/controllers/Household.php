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
use \Valitron\Validator;

///////////////////////////
// File-specific classes //
///////////////////////////
use Base\Repositories\UserRepository;
use Base\Repositories\HouseholdRepository;

class Household extends Controller{
	private $userRepo;
	public function __construct()
    {
        parent::__construct(...func_get_args());
		$dbh = DatabaseHandler::getInstance();
		$this->userRepo = new UserRepository($dbh->getDB());
		$this->hhRepo = new HouseholdRepository($dbh->getDB());
    }

	public function index(){
		$user = $this->model('User');
		$u = $this->userRepo->find($_SESSION['username']);
		$user->login($u);
		//print_r($user->getHousehold());

		$message = '';

		$this->view('/auth/newHousehold',['message' => $message]);
	}
	public function create(){
		$user = $this->model('User');
		$u = $this->userRepo->find($_SESSION['username']);
		$user->login($u);

		// create household
		$object = array();
		$object['name'] = $user->getLastName().' Household';
		$object['userId'] = $user->getId();
		$h = $this->hhRepo->insert($object);

		$this->view('/dashboard/index', ['username' => $user->getUsername(), 'name' => $user->getName(), 'profile_pic' => ($user->getUsername().'.jpg')]);
	}
}
?>
