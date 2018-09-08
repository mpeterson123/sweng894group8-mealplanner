<?php
namespace App\Controllers;

////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once('../core/DatabaseHandler.php');


/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use App\Core\DatabaseHandler;

/**
 * Super class that handles all incoming requests
 */
class Controller{
	private $dbh;

	/**
	 * Inject DatabaseHandler on instance creation
	 * @param App\Core\DatabaseHandler $dbh handler for database connection
	 */
	public function __construct(App\Core\DatabaseHandler $dbh){
		$this->$dbh = $dbh;
	}

	public function model($model){
		require_once '../models/'.$model.'.php';
		return new $model();
	}
	public function view($view,$data = []){
		require_once '../views/'.$view.'.php';
	}
}
?>
