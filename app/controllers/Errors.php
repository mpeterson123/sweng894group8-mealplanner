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
        $this->view('errors/'.$errorCode);
        return;
    }
}
