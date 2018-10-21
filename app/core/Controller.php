<?php
namespace Base\Core;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';

////////////////////
// Use statements //
////////////////////
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;
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

		$userRepository = new UserRepository($this->dbh->getDB());
		$user = (new Session())->get('user');
		$data['user'] = $user;

		$notLoggedInPages =  array('auth/login','auth/register','auth/resetPassword');

		if($user || in_array($view,$notLoggedInPages) ){
			require_once __DIR__.'/../views/'.$view.'.php';
		}
		else {
			require_once __DIR__.'/../views/auth/login.php';
		}

	}
}
?>
