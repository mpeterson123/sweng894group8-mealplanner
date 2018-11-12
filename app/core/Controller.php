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
class Controller {

	/**
	 * Renders a view (page)
	 * @param string $view View name
	 * @param array  $data Extra data to pass on to view
	 */
	public function view($view,$data = []):void{

		$user = $this->session->get('user');
		$data['user'] = $user;
		$data['session'] = $this->session;

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
