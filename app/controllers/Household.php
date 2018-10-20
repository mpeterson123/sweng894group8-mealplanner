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
use Base\Models\Household as HH;
use Base\Repositories\HouseholdRepository;
use Base\Factories\HouseholdFactory;

class Household extends Controller{
	private $userRepo;
	private $hhRepo;
	private $dbh;

	public function __construct()
    {
        parent::__construct(...func_get_args());
		$this->dbh = DatabaseHandler::getInstance();
		$this->userRepo = new UserRepository($this->dbh->getDB());
		$this->hhRepo = new HouseholdRepository($this->dbh->getDB());
    }

	public function index(){
		$user = $this->userRepo->find(Session::get('username'));
		$message = '';

		$this->view('/auth/newHousehold',['message' => $message]);
	}
	public function create(){
		$user = $this->userRepo->find(Session::get('username'));

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
	public function list(){
		$user = $this->userRepo->find(Session::get('username'));
		//$householdFactory = new HouseholdFactory($this->dbh->getDB());
		$households = 	$this->hhRepo->allForUser($user->getId());
		$hhs = array();
		foreach($households as $hh){
			$hhs[] = array('id'=>$hh->getId(),'name'=>$hh->getName(),'code'=>$hh->genInviteCode());
		}

		$this->view('/auth/householdList',['message' => '','households'=>$hhs]);
	}
	public function join(){
		$user = $this->userRepo->find(Session::get('username'));

		$inviteCode = trim($_POST['invite_code']);
		$household = new HH();
		$hhId = $household->reverseCode($inviteCode);

		$this->hhRepo->connect($user->getId(),$hhId);

		Redirect::toControllerMethod('Account', 'dashboard');
	}
}
?>
