<?php
namespace Base\Controllers;
////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once __DIR__.'/../core/Controller.php';
require_once __DIR__.'/../core/DatabaseHandler.php';
require_once __DIR__.'/../repositories/UserRepository.php';

/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\repositories\UserRepository;

class Auth extends Controller{
	private $userRepo;
	public function __construct()
    {
        parent::__construct(...func_get_args());
				$dbh = DatabaseHandler::getInstance();
				$this->userRepo = new UserRepository($dbh->getDB());
    }

	public function login(){
		session_start();
		$user = $this->model('User');
		// Submitted login form
		if(isset($_POST['login_username'])){
			$u = $this->userRepo->checkUser($_POST['login_username'],$_POST['login_password']);
			if(!$u);
			else{
				$user->login($u);
			}
		}
		// Active session
		if(isset($_SESSION['username'])){
			$u = $this->userRepo->find($_SESSION['username']);
			$user->login($u);
		}
		if($user->isLoggedIn())
			$this->view('dashboard/index', ['username' => $user->getUsername(), 'name' => $user->getName()]);
		else
			$this->view('auth/login');
	}
	public function logout(){
		session_start();
		//$user->logout();
		unset($_SESSION['username']);
		$this->view('auth/logout');
	}
}
?>
