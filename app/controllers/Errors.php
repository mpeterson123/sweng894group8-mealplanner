<?php
namespace Base\Controllers;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';

//////////////////////
// Standard classes //
//////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;
use Base\Helpers\Redirect;
use Base\Helpers\Format;
use \Valitron\Validator;

///////////////////////////
// File-specific classes //
///////////////////////////

/**
 * Displays error pages
 */
class Errors extends Controller {

    protected $dbh,
        $session;

    public function __construct(DatabaseHandler $dbh, Session $session){
		$this->dbh = $dbh;
		$this->session = $session;
    }

    /**
     * Display an error page
     * @param  integer $errorCode Error code page to display
     * @return void
     */
    public function show($errorCode)
    {
        if(!is_numeric($errorCode) || !file_exists(__DIR__.'/../views/errors/'.$errorCode.'.php')){
            $this->view('errors/500');
            return;
        }
        $this->view('errors/'.$errorCode);
        return;
    }
}
