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
use Base\Factories\UserFactory;
use Base\Helpers\Log;

/**
 * Represents a user's household. Can have multiple members.
 */
class Household extends Controller{
	protected $dbh,
		$session,
		$request,
		$log;

	private	$userRepository,
		$householdRepository,
		$householdFactory,
		$userFactory;

	public function __construct($dependencies){
		$this->dbh = $dependencies['dbh'];
		$this->session = $dependencies['session'];
		$this->request = $dependencies['request'];
		$this->log = $dependencies['log'];

		$this->householdFactory = $dependencies['householdFactory'];
		$this->householdRepository = $dependencies['householdRepository'];
		$this->userFactory = $dependencies['userFactory'];
		$this->userRepository = $dependencies['userRepository'];


    }
	/*
	 * Select to create or join a household
	 */
	public function index(){
		$user = $this->session->get('user');
		$message = '';

		$this->view('/auth/newHousehold',['message' => $message, 'name'=>$user->getLastName()]);
	}
	/*
	 * Create a household
	 */
	public function create(){
		$user = $this->session->get('user');

		$this->validateNameInput($this->request, 'index');

		// Generate household name, and create household with that, and current user as owner
		$householdName = trim($this->request['name']);
		$household = $this->householdFactory->make(array('name' => $householdName, 'owner' => $user->getUsername()));
		$this->householdRepository->save($household);

		// Get new $hhID
		$hhId = 0;
		$hhs = $this->householdRepository->allForUser($user);
		foreach($hhs as $hh){	// Get most recent hh with this hh name created by current user
			if(($hh->getName() == $householdName) && ($hh->getOwner() == $user->getUsername()))
				$hhId = $hh->getId();
		}

		// Toggle this household as current
		$this->userRepository->selectHousehold($user,$hhId);

		// Update user in the session
		$updatedUser = $this->userRepository->find($user->getUsername());
		$this->session->add('user', $updatedUser);

		// Display message and redirect
		$this->session->flashMessage('success', $household->getName().' was created.');
		Redirect::toControllerMethod('Household', 'list');

	}
	/*
	 * List all households the current user is apart of
	 */
	public function list(){
		$user = $this->session->get('user');
		// Get all households for a user
		$households = 	$this->householdRepository->allForUser($user);
		// Convert to an array for passing to the view
		$hhs = array();
		foreach($households as $hh){
			$hhs[] = array('id'=>$hh->getId(),'name'=>$hh->getName(),'code'=>$hh->genInviteCode());
		}

		// TODO Check this
		$this->view('/auth/householdList',['message' => '','households'=>$hhs, 'currHH'=>$user->getCurrHousehold()]);
	}
	/*
	 * User join household
	 */
	public function join(){
		$user = $this->session->get('user');
		// Get household id
		$inviteCode = trim($this->request['invite_code']);

		if(!preg_match('/^[a-zA-Z0-9]{1,}$/', $inviteCode)){
			$this->session->flashMessage('danger', 'Your invitation code is invalid.');
			Redirect::toControllerMethod('Household', 'index');
			return;
		}

		$household = new HH();
		$hhId = $household->reverseCode($inviteCode);

		// Add user to household
		$this->householdRepository->connect($user->getId(),$hhId);

		// Toggle this household as current
		$this->userRepository->selectHousehold($user,$hhId);

		// Update user in the session
		$updatedUser = $this->userRepository->find($user->getUsername());
		$this->session->add('user', $updatedUser);
		// Diplay message and redirect
		$this->session->flashMessage('success', 'You have joined a new household.');
		Redirect::toControllerMethod('Household', 'list');
	}

	/**
	 * Detail page, links to more options
	 * @param  integer $hhID Household id
	 */
	public function detail($hhID):void{
		// Get User
		$user = $this->session->get('user');
		// Get Household
		$household = $this->householdRepository->find($hhID);
		// Get all members of household
		$members = $this->userRepository->allForHousehold($household);


		$memberArray = array(); // Simple array for passing to the view
		// Check if user is in household
		$in_hh = false;
		foreach($members as $m){
			$memberArray[] = array('id'=>$m->getId(),'name'=>$m->getName(),'username'=>$m->getUsername());
			if($m->getId() == $user->getId())
				$in_hh = true;
		}
		if(!$in_hh){
			$this->log->add($user->getId(), 'Error', 'Household Detail - Not a memeber => Access Denied (HH: '.$hhID.')');
			$this->session->flashMessage('danger', 'You do not have access to this household.');
			Redirect::toControllerMethod('Household', 'list');
		}
		// Check if is owner
		$isOwner = false;
		if($household->getOwner() == $user->getUsername())
			$isOwner = true;

		$this->view('/auth/householdView',['message' => '','hhId'=>$household->getId(),'name'=>$household->getName(),'owner'=>$household->getOwner(),'isOwner'=>$isOwner,'members'=>$memberArray]);
	}

	/*
	 * Remove user from household

	/**
	 * Remove a user from a household
	 * @param  integer $hhId   Household id
	 * @param  integer $userId User's id
	 */
	public function remove($hhId,$userId):void{
		// Get Household
		$household = $this->householdRepository->find($hhId);

		// disconnect
		$this->householdRepository->disconnect($userId,$hhId);

		$this->session->flashMessage('success', 'The selected user has been removed from this household.');

		Redirect::toControllerMethod('Household', 'detail', array($hhId));
	}

	/**
	 * Remove current user from a household
	 * @param integer $hhId Household id
	 */
	public function leave($hhId):void{
		// Get User
		$user = $this->session->get('user');

		// disconnect
		$this->householdRepository->disconnect($user->getId(),$hhId);

		$updatedUser = $this->userRepository->find($user->getUsername());
		$this->session->add('user', $updatedUser);

		// If user has no current household after leaving, select one.
		if(!$updatedUser->getCurrHousehold()){
			$households = $this->householdRepository->allForUser($updatedUser);
			$this->userRepository->selectHousehold($updatedUser,$households[0]->getId());
		}

		$updatedUser = $this->userRepository->find($user->getUsername());
		$this->session->add('user', $updatedUser);

		$this->session->flashMessage('success', 'You left a household.');

		Redirect::toControllerMethod('Household', 'list');
	}

	/**
	 * Delete a household
	 * @param  integer $hhId Household id
	 */
	public function delete($hhId){
		// Delete Household
		if(!$this->householdRepository->remove($hhId)){
			$this->session->flashMessage('danger', 'An error occurred deleting this household. Please reload the page and try again.');
		}
		else{
			// Create default household if only household was deleted
			$user = $this->session->get('user');
			$this->log->add($user->getId(), 'Delete', 'A household ('.$hhId.') has been deleted');
			$households = 	$this->householdRepository->allForUser($user);

			$this->session->flashMessage('success', 'The selected household was deleted.');

			if(empty($households)){
				// Generate household name, and create household with that, and current user as owner
				$householdName = $user->getLastName().' Household';
				$household = $this->householdFactory->make(array('name' => $householdName, 'owner' => $user->getUsername()));
				$this->householdRepository->save($household);
				// Update user in the session
				$updatedUser = $this->userRepository->find($user->getUsername());
				$this->session->add('user', $updatedUser);
				// Display message and redirect
				$this->session->flashMessage('success', 'The selected household was deleted. Since it was your only household, an empty default household was created for you.');
			}
			// If selected household is deleted toggle to first household in list
			if($user->getCurrHousehold()->getId() == $hhId){
				$user = $this->session->get('user');
				$households = 	$this->householdRepository->allForUser($user);
				$this->userRepository->selectHousehold($user,$households[0]->getId());

				// Update user in the session
				$updatedUser = $this->userRepository->find($user->getUsername());
				$this->session->add('user', $updatedUser);

				$this->session->flashMessage('success', 'The selected household was deleted. Since it was your current household, a new one was assigned.');
			}
		}
		// Redirect to list
		Redirect::toControllerMethod('Household', 'list');
	}

	/**
	 * Switch current household
	 * @param integer $hhId Household id
	 */
	public function select($hhId):void{
		// change selected household
		$user = $this->session->get('user');
		$this->userRepository->selectHousehold($user,$hhId);

		// Update user in the session
		$updatedUser = $this->userRepository->find($user->getUsername());
		$this->session->add('user', $updatedUser);

		$this->session->flashMessage('success', 'Your household switched to: '.$updatedUser->getCurrHousehold()->getName());

		// Redirect to list
		Redirect::toControllerMethod('Household', 'list');
	}
	/**
	 * Rename household
	 * @param integer $hhId Household id
	 */
	public function rename($hhId):void{
		$user = $this->session->get('user');

		// Validate input
		$this->validateNameInput($this->request, 'detail', [$hhId]);

		// Get household
		$newHouseholdName = $this->request['name'];
		$household = $this->householdRepository->find($hhId);

		if(!$household){
			$this->session->flashMessage('danger', 'Uh oh, the household is invalid.');
			Redirect::toControllerMethod('Household', 'list');
			return;
		}

		// Check if requester is the owner of the household
		if($household->getOwner() != $user->getUsername()){
			$this->session->flashMessage('danger', 'You are not the owner of this household');
			Redirect::toControllerMethod('Household', 'detail', array($hhId));
			return;
		}

		// Rename
		$household->setName($newHouseholdName);
		if(!$this->householdRepository->save($household)){
			$this->session->flashMessage('danger', 'Uh oh, the household could not be renamed.');
			Redirect::toControllerMethod('Household', 'detail', array($hhId));
			return;
		};

		// Update user in the session
		$updatedUser = $this->userRepository->find($user->getUsername());
		$this->session->add('user', $updatedUser);

		// Display message and redirect
		$this->session->flashMessage('success', $household->getName().' was renamed');
		Redirect::toControllerMethod('Household', 'detail', array($hhId));
	}

	/**
     * Validates household name input from creation/renaming forms
     * @param array $input      Household name input
     * @param string $method    Method to redirect to
     * @param array $params     Parameters for the redirection method
     */
    private function validateNameInput($input, $method, $params = NULL):void {
        $this->session->flashOldInput($input);

        // Validate input
        $validator = new Validator($input);
        $safeStringRegex = '/^[0-9a-z #\/\(\)-]+$/i';
        $rules = [
            'required' => [
                ['name'],
            ],
            'regex' => [
                ['name', $safeStringRegex]
            ],
            'lengthMin' => [
                ['name', 1]
            ],
            'lengthMax' => [
                ['name', 32]
            ]
        ];
        $validator->rules($rules);

        if(!$validator->validate()) {

            $errorMessage = Format::validatorErrors($validator->errors());
            // Flash danger message
            $this->session->flashMessage('danger', $errorMessage);

            // Redirect back with errors
            Redirect::toControllerMethod('Household', $method, $params);
            return;
        }
    }
}
?>
