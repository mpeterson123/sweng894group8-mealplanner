<?php
namespace Base\Core;

////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once('DatabaseHandler.php');


/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use Base\Core\DatabaseHandler;


/**
 * Entry point to the application
 *
 * This class receives all requests and calls the appropriate controller methods
 * to handle them.
 */
class App {
	protected $controller = 'Home';
	protected $method = 'index';
	protected $params = [];

	public function __construct(){

		// set timezone
		date_default_timezone_set('America/New_York');

		$dbh = new DatabaseHandler();
		$url = $this->parseUrl();

		// Get and set controller
		if(file_exists(__DIR__.'/../controllers/'.$url[0].'.php')){
			$this->controller = $url[0];
			unset($url[0]);
		}

		$path = __DIR__.'/../controllers/'.$this->controller.'.php';
		require_once($path);

		$namespacedController = "Base\Controllers\\".$this->controller;
		$this->controller = new $namespacedController($dbh);

		// Get and set method
		if(isset($url[1])){
			if(method_exists($this->controller,$url[1])){
				$this->method = $url[1];
				unset($url[1]);
			}
		}
		$this->params = $url ? array_values($url) : [];

		call_user_func_array([$this->controller,$this->method],$this->params);
	}

	public function parseUrl(){
		if(isset($_GET['url'])){
			return $url = explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
		}
	}
}
?>
