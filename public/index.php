<?php
// Change to false to disable showing and reporting errors
$DEBUG = true;
if($DEBUG){
    ini_set('display_errors', true);
    error_reporting(E_ALL);
}

require_once __DIR__.'/../vendor/autoload.php';
use Base\Core\App;

// Set default timezone
date_default_timezone_set('America/New_York');

// Run app
$app = new App();
