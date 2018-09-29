<?php
namespace Base\Controllers;

// Autoload dependencies
require_once __DIR__.'/../../vendor/autoload.php';

////////////////////
// Use statements //
////////////////////
use Base\Core\Controller;
use Base\Core\DatabaseHandler;

class Errors extends Controller {

    private $dbh;

    public function __construct()
    {
        parent::__construct(...func_get_args());
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
