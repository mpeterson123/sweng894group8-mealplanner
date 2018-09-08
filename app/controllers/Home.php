<?php
namespace Base\Controllers;

////////////////////////////////////////////////////////////
// Import dependencies. Can be replaced by autoload later //
////////////////////////////////////////////////////////////
require_once __DIR__.'/../core/Controller.php';


/////////////////////////////////////////////////////////////////////
// Load dependencies into current scope. Not the same as importing //
/////////////////////////////////////////////////////////////////////
use Base\Core\Controller;

class Home extends Controller{

	public function __construct()
    {
        parent::__construct(...func_get_args());
    }

	public function index($name=''){
		$user = $this->model('User');
		$user->name = $name;

		$this->view('home/index', ['name' => $user->name]);
	}
}
?>
