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
	private $householdFactory;

	public function __construct()
    {
        parent::__construct(...func_get_args());
		// TODO use dependecy injection
		$this->dbh = DatabaseHandler::getInstance();
		$this->userRepo = new UserRepository($this->dbh->getDB());
		$this->hhRepo = new HouseholdRepository($this->dbh->getDB());
		$this->householdFactory = new HouseholdFactory();
    }

	public function index(){
		$user = (new Session())->get('user');
		$message = '';

		$this->view('/auth/newHousehold',['message' => $message]);
	}
	public function create(){
		$user = (new Session())->get('user');

		$householdName = $user->getLastName().' Household';
		$household = $this->householdFactory->make(array('name' => $householdName));
		$this->hhRepo->save($household);

		// Update user in the session
		$updatedUser = $this->userRepo->find($user->getUsername());
		(new Session())->add('user', $updatedUser);

		(new Session())->flashMessage('success', $household->getName().' was created. Check the Household Settings page to see the invite code for other users.');
		Redirect::toControllerMethod('Account', 'dashboard');

	}
	public function list(){
		$user = (new Session())->get('user');

		$households = 	$this->hhRepo->allForUser($user);
		$hhs = array();
		foreach($households as $hh){
			$hhs[] = array('id'=>$hh->getId(),'name'=>$hh->getName(),'code'=>$hh->genInviteCode());
		}

		$this->view('/auth/householdList',['message' => '','households'=>$hhs]);
	}
	public function join(){
		$user = (new Session())->get('user');

		$inviteCode = trim($_POST['invite_code']);
		$household = new HH();
		$hhId = $household->reverseCode($inviteCode);

		$this->hhRepo->connect($user->getId(),$hhId);

		// Update user in the session
		$updatedUser = $this->userRepo->find($user->getUsername());
		(new Session())->add('user', $updatedUser);

		(new Session())->flashMessage('success', 'You have joined a household.');
		Redirect::toControllerMethod('Account', 'dashboard');
	}
}
?>
