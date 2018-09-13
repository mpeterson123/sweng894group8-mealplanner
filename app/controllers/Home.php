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
		session_start();
		$user = $this->model('User');
		// Submitted login form
		if(isset($_POST['login_username'])){
			$u = $this->userRepo->checkUser($_POST['login_username'],$_POST['login_password']);
			if(!$u);
			else{
				$user->setUsername($u['username']);
				$_SESSION['username'] = $u['username'];
				$user->login();
			}
		}
		// Active session
		if(isset($_SESSION['username'])){
			$user->setUsername($_SESSION['username']);
			$user->login();
		}
		if($user->isLoggedIn())
			$this->view('dashboard/index', ['name' => $user->getUsername()]);
		else
			$this->view('auth/login');
	}
}
?>
