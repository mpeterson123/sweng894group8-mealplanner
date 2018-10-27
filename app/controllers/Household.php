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
	/*
	 * Select to create or join a household
	 */
	public function index(){
		$user = (new Session())->get('user');
		$message = '';

		$this->view('/auth/newHousehold',['message' => $message]);
	}
	/*
	 * Create a household
	 */
	public function create(){
		$user = (new Session())->get('user');
		// Generate household name, and create household with that, and current user as owner
		$householdName = $user->getLastName().' Household';
		$household = $this->householdFactory->make(array('name' => $householdName, 'owner' => $user->getUsername()));
		$this->hhRepo->save($household);

		// Update user in the session
		$updatedUser = $this->userRepo->find($user->getUsername());
		(new Session())->add('user', $updatedUser);
		// Display message and redirect
		(new Session())->flashMessage('success', $household->getName().' was created. Check the Household Settings page to see the invite code for other users.');
		Redirect::toControllerMethod('Account', 'dashboard');

	}
	/*
	 * List all households the current user is apart of
	 */
	public function list(){
		$user = (new Session())->get('user');
		// Get all households for a user
		$households = 	$this->hhRepo->allForUser($user);
		// Convert to an array for passing to the view
		$hhs = array();
		foreach($households as $hh){
			$hhs[] = array('id'=>$hh->getId(),'name'=>$hh->getName(),'code'=>$hh->genInviteCode());
		}

		$this->view('/auth/householdList',['message' => '','households'=>$hhs]);
	}
	/*
	 * User join household
	 */
	public function join(){
		$user = (new Session())->get('user');
		// Get household id
		$inviteCode = trim($_POST['invite_code']);
		$household = new HH();
		$hhId = $household->reverseCode($inviteCode);
		// Add user to household
		$this->hhRepo->connect($user->getId(),$hhId);
		// Update user in the session
		$updatedUser = $this->userRepo->find($user->getUsername());
		(new Session())->add('user', $updatedUser);
		// Diplay message and redirect
		(new Session())->flashMessage('success', 'You have joined a household.');
		Redirect::toControllerMethod('Account', 'dashboard');
	}
	/*
	 * Detail page, links to more options
	 */
	public function detail($hhID){
		// Get User
		$user = (new Session())->get('user');
		// Get Household
		$household = $this->hhRepo->find($hhID);
		// Get all members of household
		$members = $this->hhRepo->allForHousehold($household);
		$memberArray = array(); // Simple array for passing to the view
		// Check if user is in household
		$in_hh = false;
		foreach($members as $m){
			$memberArray[] = array('id'=>$m->getId(),'name'=>$m->getName(),'username'=>$m->getUsername());
			if($m->getId() == $user->getId())
				$in_hh = true;
		}
		if(!$in_hh){
			die('You do not have access to this household');
		}
		// Check if is owner
		$isOwner = false;
		if($household->getOwner() == $user->getUsername())
			$isOwner = true;

		$this->view('/auth/householdView',['message' => '','hhId'=>$household->getId(),'name'=>$household->getName(),'owner'=>$household->getOwner(),'isOwner'=>$isOwner,'members'=>$memberArray]);
	}
	/*
	 * Remove user from household
	 */
	public function remove($hhId,$userId){
		// Get Household
		$household = $this->hhRepo->find($hhId);

		// disconnect
		$this->hhRepo->disconnect($userId,$hhId);

		Redirect::toControllerMethod('Household', 'detail', array($hhId));
	}
	/*
	 * Leave househould / User remove themself from household
	 */
	public function leave(){

	}
	/*
	 * Delete household
	 */
	public function delete(){

	}

}
?>
