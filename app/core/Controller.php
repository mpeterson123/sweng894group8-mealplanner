<?php
namespace Base\Core;

////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once('DatabaseHandler.php');
require_once(__DIR__ . '/../repositories/UserRepository.php');

/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use Base\Core\DatabaseHandler;
use Base\Repositories\UserRepository;

/**
 * Super class that handles all incoming requests
 */
class Controller{
	private $dbh;

	/**
	 * Inject DatabaseHandler on instance creation
	 * @param Base\Core\DatabaseHandler $dbh handler for database connection
	 */
	public function __construct(DatabaseHandler $dbh){
		$this->dbh = $dbh;
	}

	public function model($model, $params = NULL){
		require_once __DIR__.'/../models/'.$model.'.php';
		$namespacedModel = "Base\Models\\".$model;

		if($params){
			return new $namespacedModel(...$params);
		}
		return new $namespacedModel();

	}
	public function view($view,$data = []){
		// session_start();

		$userRepository = new UserRepository($this->dbh->getDB());

		if(isset($_SESSION['username'])){
			$user = $userRepository->find($_SESSION['username']);
			$data['user'] = $user;
			require_once __DIR__.'/../views/'.$view.'.php';
		}
		else {
			require_once __DIR__.'/../views/auth/login.php';
		}

	}
}
?>
