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
use Base\Repositories\UserRepository;
use Base\Repositories\HouseholdRepository;
use Base\Factories\HouseholdFactory;

class Household extends Controller{
	private $userRepo;
	private $dbh;

	public function __construct()
    {
        parent::__construct(...func_get_args());
		$this->dbh = DatabaseHandler::getInstance();
		$this->userRepo = new UserRepository($this->dbh->getDB());
		$this->hhRepo = new HouseholdRepository($this->dbh->getDB());
    }

	public function index(){
		$user = $this->userRepo->find((new Session())->get('username'));
		$message = '';

		$this->view('/auth/newHousehold',['message' => $message]);
	}
	public function create(){
		$user = $this->userRepo->find((new Session())->get('username'));

		$householdName = $user->getLastName().' Household';
		$householdFactory = new HouseholdFactory($this->dbh->getDB());
		$household = $householdFactory->make(array('name' => $householdName));
		$household = $this->hhRepo->save($household);
		// // create household
		// $object = array();
		// $object['name'] = $user->getLastName().' Household';
		// $object['userId'] = $user->getId();
		// $h = $this->hhRepo->insert($object);
		$this->view('/dashboard/index', ['username' => $user->getUsername(), 'name' => $user->getName(), 'profile_pic' => ($user->getUsername().'.jpg')]);
	}
}
?>
