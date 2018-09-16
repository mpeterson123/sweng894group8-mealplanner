<?php
namespace Base\Controllers;
////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once __DIR__.'/../core/Controller.php';
require_once __DIR__.'/../core/DatabaseHandler.php';
require_once __DIR__.'/../repositories/UserRepository.php';
require_once __DIR__.'/../models/Email.php';

/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\repositories\UserRepository;
use Base\models\Email;

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
					$email->send($user['email'],'Please confirm your email', 'Please click this link to confirm your email address <INSERT LINK HERE>');
					$this->userRepo->insert($user);
					$this->view('auth/login',array('message'=>'Account has been created. Please Login.'));
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
	function pass_hash($password){
		for($i = 0; $i < 1000; $i++) $password = hash('sha256',trim(addslashes($password)));
		return $password;
	}
}
?>
