<?php
// Change to false to disable showing and reporting errors
$DEBUG = true;
if($DEBUG){
    ini_set('display_errors', true);
    error_reporting(E_ALL);
}

require_once __DIR__.'/../vendor/autoload.php';
use Base\Core\App;
use Base\Core\DatabaseHandler;
use Base\Helpers\Session;

// Set default timezone
date_default_timezone_set('America/New_York');

// Start session
session_start();

// Instantiate global dependencies
$dbh = DatabaseHandler::getInstance();
$session = new Session();
$request = $_REQUEST;

// Run app
$app = new App($dbh, $session, $request);
$app->run();
