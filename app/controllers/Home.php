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

class Home extends Controller{
	private $userRepo;
	public function __construct()
    {
        parent::__construct(...func_get_args());
				$dbh = DatabaseHandler::getInstance();
				$this->userRepo = new UserRepository($dbh->getDB());
    }

	public function index(){
		$user = $this->model('User');
		$message = '';
		// Submitted login form
		if(isset($_POST['login_username'])){
			$pwd = $this->pass_hash($_POST['login_password']);
			$u = $this->userRepo->checkUser($_POST['login_username'],$pwd);
			if(!$u)	$message = 'Incorrect Username or Password';
			else{
				$user->login($u);
			}
		}
		// Active session
		else if(isset($_SESSION['username'])){
			$u = $this->userRepo->find($_SESSION['username']);
			$user->login($u);
		}
		if($user->isLoggedIn())
			$this->view('dashboard/index', ['username' => $user->getUsername(), 'name' => $user->getName()]);
		else
			$this->view('auth/login',['message' => $message]);
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
