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
	protected $dbh;
	protected $session;
	protected $request;
	protected $log;

	public function __construct($dbh, $session, $request, $log, $loader){
		$this->dbh = $dbh;
		$this->session = $session;
		$this->log = $log;
		$this->url = isset($request['url']) ? $request['url'] : '';
		$this->loader = $loader;
		unset($request['url']);

		// Sanitize Input
		$this->request = $this->sanitizeArray($request);


	}
	public function sanitizeArray($array){
		foreach($array as $k => $v){
			if(is_array($v))
				$array[$k] = $this->sanitizeArray($v);
			else if($v !== NULL)
				$array[$k] = $this->sanitizeString($v);
		}
		return $array;
	}
	public function sanitizeString($string){
		return htmlspecialchars(addslashes(trim($string)));
	}

	/**
	 * Sanitizes and break URL into chunks
	 */
	private function parseUrl():void{
		if(isset($this->url)){
			$this->url = explode('/',filter_var(rtrim($this->url,'/'),FILTER_SANITIZE_URL));
		}
	}

	/**
	 * Runs the app by transfering control to the correct controller method
	 */
	public function run():void {
		$defaultControllerName = 'Account';
		$controllerName = 'Account';
		$methodName = 'showLogin';
		$controller;
		$params = [];

		$this->parseUrl();

		if(($this->session->get('user') === NULL) && ($this->url[0] != "Account")){
			$controller = new \Base\Controllers\Account($this->dbh,new Session(),NULL, NULL);
		}
		else if(!empty($this->url[0])){		// otherwise use default
			try{
				// If controller file exists, set it and remove the name from the URL
				if(file_exists(__DIR__.'/../controllers/'.$this->url[0].'.php')){
					$controllerName = $this->url[0];
					unset($this->url[0]);

					// Set shared dependencies
					$sharedDependencies = array(
						'dbh' => $this->dbh,
						'session' => $this->session,
						'request' => $this->request,
						'log' => $this->log,
					);

					// Load dependencies for the controller
					$namespacedControllerDependencyLoader = "Base\Loaders\\".$controllerName.'Loader';
					$controllerDependencyLoader = new $namespacedControllerDependencyLoader($this->loader);
					$dependencies = array_merge($sharedDependencies, $controllerDependencyLoader->loadDependencies());

					// Instantiate controller
					$namespacedController = "Base\Controllers\\".$controllerName;
					$controller = new $namespacedController($dependencies);


					// If methodName exists, set it and remove the name from the URL
					if(isset($this->url[1]) && method_exists($controller,$this->url[1]))
					{
						$methodName = $this->url[1];
						unset($this->url[1]);
					}
					else {
						throw new \Exception("Method does not exist", 1);
					}
					// Get params if any
					$params = $this->url ? array_values($this->url) : [];
				}
				else{
					throw new \Exception("Controller does not exist", 1);
				}
			}
			catch(\Exception $e) {
				// Instantiate controller
				$namespacedController = "Base\Controllers\\Errors";
				$controller = new $namespacedController($this->dbh, $this->session, $this->request);
				$methodName = 'show';
				$params = array('errorCode'=>404);
			}
		}
		else{
			$namespacedController = "Base\Controllers\\".$controllerName;
			$controller = new $namespacedController($this->dbh, $this->session, $this->request);
		}

		try {
			// Invoke controller methodName with parameters
			call_user_func_array([$controller,$methodName],$params);
		}
		catch(\ArgumentCountError $ace){
			$namespacedController = "Base\Controllers\\Errors";
			$controller = new $namespacedController($this->dbh, $this->session, $this->request);
			$methodName = 'show';
			$params = array('errorCode'=>400);
			call_user_func_array([$controller,$methodName],$params);

		}
	}
}
