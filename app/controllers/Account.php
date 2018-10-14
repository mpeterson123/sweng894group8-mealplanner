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
use Base\Helpers\Email;
use Base\Models\User;

class Account extends Controller{
	private $userRepo;

	public function __construct() {
      parent::__construct(...func_get_args());
			$dbh = DatabaseHandler::getInstance();
			$this->userRepo = new UserRepository($dbh->getDB());
  	}

	public function register(){
		if(isset($_POST['reg_username'])){
			$error = array();
			$user = array();
			$fields = array('username','password','namefirst','namelast','email');
			foreach($fields as $f){
				$user[$f] = $_POST['reg_'.$f];
				if(!isset($user[$f])){
					$error[] = 'All fields are required';
				}
			}
			if($_POST['reg_password'] != $_POST['reg_password2']){
				$error[] = 'Passwords don\'t match';
			}
			if(empty($error)){
					$user['password'] = $this->pass_hash($user['password']);
					$email = new Email();
					$email->sendEmailAddrConfirm($user['email']);
					$this->userRepo->insert($user);

					Session::flashMessage('success', 'Your account has been created. A confirmation link has been sent to you. Please confirm your email address to activate your account.');
					Redirect::toControllerMethod('Account', 'showLogin');
			}
			else {
				Session::flashMessage('danger', 'An error occured');
				$this->view('auth/register',$error);
			}
		}
		else
			$this->view('auth/register');
	}

	public function logout(){
		unset($_SESSION['username']);
		Redirect::toControllerMethod('Account', 'showLogin');
	}

	public function pass_hash($password){
		for($i = 0; $i < 1000; $i++) $password = hash('sha256',trim(addslashes($password)));
		return $password;
	}

	public function confirmEmail($email,$code){
		// Handle circumvention of email confirmation
		$salt = 'QM8z7AnkXUKQzwtK7UcA';
		if(urlencode(hash('sha256',$email.$salt) != $code)){
			Session::flashMessage('danger', 'Your password reset link is invalid. Please reset your password again.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}

		// set as confirmed in the db
		$this->userRepo->confirmEmail($email);

		// Redirect to login
		Session::flashMessage('success', 'Your email address has been confirmed. Please log in.');
		Redirect::toControllerMethod('Account', 'showLogin');

	}

	public function forgotPassword(){
		// Get temp pass code and email
		$code = urlencode(hash('sha256',rand(1000000000,10000000000)));
		$email = addslashes(trim($_POST['email']));

		// Check if email exists in db
		$u = $this->userRepo->get('email',$email);
		if($email == '')
			$this->view('auth/login',['message'=>'No email has been supplied.']);
		else if(!$u)
			$this->view('auth/login',['message'=>'Not Found. An email has been sent with instruction to reset your password.']);
		else{
			$this->userRepo->setPassTemp($email,$code);
			// send Email
			$emailHandler = new Email();
			$emailHandler->sendPasswordReset($email,$code);

			// Redirect to login
			$this->view('auth/login',['message'=>'An email has been sent with instruction to reset your password.']);
		}
	}

	public function resetPassword($email,$code){
		// Check if email exists in db
		$u = $this->userRepo->get('email',$email);
		if(!$u)
			$this->view('auth/login',['message'=>'An error has occured. Please try again. Email.']);
		// Check if reset code has been set
		else if($u['passTemp'] == '')
			$this->view('auth/login',['message'=>'An error has occured. Please try again. tempPass not set.']);
		// Check if code matches db
		else if($u['passTemp'] != $code)
			$this->view('auth/login',['message'=>'An error has occured. Please try again. Code.']);
		else{
			// Reset page has been submitted
			if(isset($_POST['rst_password'])){
				// Reset password
				$this->userRepo->setValue('password',$this->pass_hash($_POST['rst_password']),'email',$email);
				// Reset temp pass
				$this->userRepo->setValue('passTemp','','email',$email);
				// Redirect to login
				$this->view('auth/login',['message'=>'Password has been reset. Please login.']);
			}
			else{
				// Direct to reset pass view
				$this->view('auth/resetPassword',['email'=>$email,'code'=>$code]);
			}
		}
	}

	public function settings(){
		$user = $this->userRepo->find(Session::get('username'));
		$this->view('auth/settings', compact($user));
	}


	public function update(){
		$user = $this->userRepo->find($_SESSION['username']);

		// Check for blank fields
		$fields = array('firstName','lastName','email');
		foreach($fields as $f){
			if(!isset($_POST[$f])){
				die('All fields are required');
			}
		}
		// Handle password update
		if(isset($_POST['password'])){
			if($_POST['password'] != $_POST['confirmPassword']){
				die('Passwords don\'t match');
			}
			$user->setPassword($this->pass_hash($_POST['password']));
		}
		// Handle name updated
		if($_POST['firstName'].' '.$_POST['lastName'] != $user->getFirstName().' '.$user->getLastName()){
			$user->setFirstname($_POST['firstName']);
			$user->setLastName($_POST['lastName']);
		}

		$this->userRepo->save($user);

		// Handle email updated
		if($_POST['email'] != $user->getEmail()){
			// send Email
			$emailHandler = new Email();
			$emailHandler->sendEmailUpdateConfirm($_POST['email'],$user->getEmail());
			Session::flashMessage('success', 'A confirmation email has been sent to '.$_POST['email'].'. Please confirm to update.');
			Redirect::toControllerMethod('Account', 'settings');
			return;
		}

		Session::flashMessage('success', 'Your account has been updated. Return to <a href="/Account/dashboard/">Dashboard</a>.');
		Redirect::toControllerMethod('Account', 'settings');

	}


	public function confirmNewEmail($email,$old_email,$code){
		// Handle circumvention of email confirmation
		$salt = 'QM8z7AnkXUKQzwtK7UcA';
		if(urlencode(hash('sha256',$email.$salt.$old_email) != $code)){
			Session::flashMessage('danger', 'Your email confirmation link is invalid.');
			Redirect::toControllerMethod('Account', 'showLogin');
		}

		// update in the db
		$this->userRepo->setValue('email',$email,'email',$old_email);

		// Redirect to login
		Session::flashMessage('success', 'Your email address has been updated.');
		Redirect::toControllerMethod('Account', 'dashboard');

	}


	public function delete($confirmed = 0){
		// Confirm
		if(!$confirmed){
				$this->view('auth/settings', ['message'=>'Are you sure you want to delete? This cannot be undone. <a href="/Account/delete/'.$_SESSION['id'].'">Yes</a><br><a href="/Account/dashboard">Back to dashboard.</a>']);
		}
		// Delete User and all related info
		else{
			$this->userRepo->remove($_SESSION['id']);
			// !!!!
 			// Remove data from other repos here
			// !!!!
			unset($_SESSION['id']);
			unset($_SESSION['username']);
			$this->view('auth/login',['message'=>'Your account has been deleted.']);
		}
	}

	public function dashboard(){
		$user = $this->userRepo->find($_SESSION['username']);

		if(empty($user->getHouseholds())){
			$this->view('/auth/newHousehold',['message' => $message]);
			return;
		}

		$user = $this->userRepo->find($_SESSION['username']);
		$this->view('dashboard/index', ['username' => $user->getUsername(), 'name' => $user->getName(), 'profile_pic' => ($user->getUsername().'.jpg')]);
	}

	public function showLogin(){
		$this->view('auth/login');
	}

	public function logInUser(){
		// Active session
		if(isset($_SESSION['username'])){
			Redirect::toControllerMethod('Account', 'dashboard');
		}

		// Submitted login form
		if(isset($_POST['login_username'])){
			$pwd = $this->pass_hash($_POST['login_password']);
			$user = $this->userRepo->checkUser($_POST['login_username'],$pwd);
			if(!$user){
				$message = 'Incorrect username or password.';
			}
			else if(!$user->getActivated()){
				$message = 'Please confirm email before you can log in.';
			}
			else{
				$_SESSION['username'] = $user->getUsername();
				$_SESSION['id'] = $user->getId();

				Redirect::toControllerMethod('Account', 'dashboard');
				return;
			}
		}
		Session::flashMessage('danger', $message);
		Redirect::toControllerMethod('Account', 'showLogin');
	}


}
?>
