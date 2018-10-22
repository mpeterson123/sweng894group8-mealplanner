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
	protected $defaultControllerName = 'Account';
	protected $controllerName = 'Account';
	protected $methodName = 'showLogin';
	protected $controller;
	protected $params = [];

	public function __construct(){

		session_start();
		// set timezone
		date_default_timezone_set('America/New_York');

		$dbh = DatabaseHandler::getInstance();
		$url = $this->parseUrl();

		try{
			// If controller file exists, set it and remove the name from the URL
			if(file_exists(__DIR__.'/../controllers/'.$url[0].'.php')){
				$this->controllerName = $url[0];
				unset($url[0]);

				// Instantiate controller
				$namespacedController = "Base\Controllers\\".$this->controllerName;
				$this->controller = new $namespacedController($dbh);

				// If methodName exists, set it and remove the name from the URL
				if(isset($url[1]) && method_exists($this->controller,$url[1]))
				{
					$this->methodName = $url[1];
					unset($url[1]);
				}
				else {
					throw new \Exception("Error Processing Request", 1);
				}
				// Get params if any
				$this->params = $url ? array_values($url) : [];
			}
			else{
				throw new \Exception("Error Processing Request", 1);
			}
		}
		catch(\Exception $e) {

			// Instantiate controller
			$namespacedController = "Base\Controllers\\Errors";
			$this->controller = new $namespacedController($dbh);
			$this->methodName = 'show';
			$this->params = array('errorCode'=>404);
		}

		// Invoke controller methodName with parameters
		call_user_func_array([$this->controller,$this->methodName],$this->params);

	}

	private function parseUrl(){
		if(isset($_GET['url'])){
			return $url = explode('/',filter_var(rtrim($_GET['url'],'/'),FILTER_SANITIZE_URL));
		}
	}
}
?>
