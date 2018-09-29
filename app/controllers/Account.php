<?php
namespace Base\Controllers;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';

////////////////////
// Use statements //
////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Repositories\UserRepository;
use Base\Models\Email;
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
					$this->view('auth/login',array('message'=>'Account has been created. A confirmation link has been sent to you. Please confirm your email address to enable your account.'));
			}
			else
					$this->view('auth/register',$error);
		}
		else
			$this->view('auth/register');
	}
	public function logout(){
		//$user->logout();
		unset($_SESSION['username']);
		$this->view('auth/logout');
	}
	public function pass_hash($password){
		for($i = 0; $i < 1000; $i++) $password = hash('sha256',trim(addslashes($password)));
		return $password;
	}
	public function confirmEmail($email,$code){
		// Handle circumvention of email confirmation
		$salt = 'QM8z7AnkXUKQzwtK7UcA';
		if(urlencode(hash('sha256',$email.$salt) != $code))	die("This link is invalid");

		// set as confirmed in the db
		$this->userRepo->confirmEmail($email);

		// Redirect to login
		$this->view('auth/login',['message'=>'Your email address has been confirmed. Please Login.']);
	}
	public function forgotPassword(){
		// Get temp pass and email
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
		$user = new User();
		$u = $this->userRepo->find($_SESSION['username']);
		$user->setAll($u);
		$this->view('auth/settings', $u);
	}
	public function update(){
		$user = new User();
		$u = $this->userRepo->find($_SESSION['username']);
		$user->setAll($u);
		// Check for blank fields
		$fields = array('namefirst','namelast','email');
		foreach($fields as $f){
			if(!isset($_POST[$f])){
				die('All fields are required');
			}
		}
		// Handle password update
		if(isset($_POST['password'])){
			if($_POST['password'] != $_POST['password2']){
				die('Passwords don\'t match');
			}
			$this->userRepo->setValue('password',$this->pass_hash($_POST['password']),'username',$_SESSION['username']);
		}
		// Handle name updated
		if($_POST['namefirst'].' '.$_POST['namelast'] != $u['namefirst'].' '.$u['namelast']){
			$this->userRepo->setValue('namefirst',$_POST['namefirst'],'username',$_SESSION['username']);
			$this->userRepo->setValue('namelast',$_POST['namelast'],'username',$_SESSION['username']);
		}
		// Handle email updated
		if($_POST['email'] != $u['email']){
			// send Email
			$emailHandler = new Email();
			$emailHandler->sendEmailUpdateConfirm($_POST['email'],$u['email']);
			$u['message'] = 'A confirmation email has been sent to '.$_POST['email'].'. Please confirm to update.';
			$this->view('auth/settings', $u);
		}
		else{
			$u['message'] = 'Your account has been updated. Return to <a href="/Dashboard/">Dashboard</a>.';
			$this->view('auth/settings', $u);
		}
	}
	public function confirmNewEmail($email,$old_email,$code){
		// Handle circumvention of email confirmation
		$salt = 'QM8z7AnkXUKQzwtK7UcA';
		if(urlencode(hash('sha256',$email.$salt.$old_email) != $code))	die("This link is invalid");

		// update in the db
		$this->userRepo->setValue('email',$email,'email',$old_email);

		// Redirect to login
		$this->view('auth/settings',['message'=>'Your email address has been updated. Return to <a href="/Dashboard/">Dashboard</a>.']);
	}
	public function delete($confirmed = 0){
		// Confirm
		if(!$confirmed){
				$this->view('auth/settings', ['message'=>'Are you sure you want to delete? This cannot be undone. <a href="/Account/delete/1">Yes</a><br><a href="/Home/">Back to dashboard.</a>']);
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
}
?>
