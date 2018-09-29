<?php
namespace Base\Core;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';

////////////////////
// Use statements //
////////////////////
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;

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

		session_start();
		// set timezone
		date_default_timezone_set('America/New_York');

		$dbh = DatabaseHandler::getInstance();
		$url = $this->parseUrl();

		// If controller file exists, set it and remove the name from the URL
		if(file_exists(__DIR__.'/../controllers/'.$url[0].'.php')){
			$this->controller = $url[0];
			unset($url[0]);
		}

		// Require controller file
		$path = __DIR__.'/../controllers/'.$this->controller.'.php';
		require_once($path);

		// Instantiate controller
		$namespacedController = "Base\Controllers\\".$this->controller;
		$this->controller = new $namespacedController($dbh);

		// If method exists, set it and remove the name from the URL
		if(isset($url[1])){
			if(method_exists($this->controller,$url[1])){
				$this->method = $url[1];
				unset($url[1]);
			}
		}
		// Get params if any
		$this->params = $url ? array_values($url) : [];

		// Invoke controller method with parameters
		call_user_func_array([$this->controller,$this->method],$this->params);

	}

	public function parseUrl(){
		if(isset($_GET['url'])){
			return $url = explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
		}
	}
}
?>
