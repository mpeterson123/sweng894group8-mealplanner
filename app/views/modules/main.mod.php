<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Primary / Foundation Module
///////////////////////////////////////////////////////////////////////////////

// Constants
define( '__SITENAME__' , 'MealPlanner - Capstone Project' );
define( '__REDIRECT__' , '/dashboard/' );
define( '__COPYRITE__' , '2018 © Penn State - Cohort 19 &amp; 20 - Group 8.' );

// Modules
define( '__HEADER__' , $_SERVER['DOCUMENT_ROOT'] . '/modules/header.mod.php');
define( '__FOOTER__' , $_SERVER['DOCUMENT_ROOT'] . '/modules/footer.mod.php');
define( '__NAVBAR__' , $_SERVER['DOCUMENT_ROOT'] . '/modules/navbar.mod.php');
define( '__SPANEL__' , $_SERVER['DOCUMENT_ROOT'] . '/modules/spanel.mod.php');
define( '__SIDEBAR__', $_SERVER['DOCUMENT_ROOT'] . '/modules/sidebar.mod.php');
// Non-HTML modules
define( '__SQL__'    , $_SERVER['DOCUMENT_ROOT'] . '/modules/sql.mod.php');
define( '__CRYPT__'  , $_SERVER['DOCUMENT_ROOT'] . '/modules/crypt.mod.php');
define( '__SESSIONS__',$_SERVER['DOCUMENT_ROOT'] . '/modules/sessions.mod.php');
define( '__PHPUNIT__', $_SERVER['DOCUMENT_ROOT'] . '/modules/phpunit.mod.php');

// Plugins (should contain every plugin that will be used in multiple scripts)
$PLUGIN_SLIMSCROLL = FALSE;
$PLUGIN_WAVES      = FALSE;
$PLUGIN_SIDEBARMENU= FALSE;          // Any page that has the sidebar needs this plugin
$PLUGIN_CHARTIST   = FALSE;          // When using charts, this plugin required
$PLUGIN_DATATABLES = FALSE;          // For (Data) Tables
$PLUGIN_EXPORT     = FALSE;          // For Data Tables that can export

// Globals
$_SERVER['phpunit_off'] = TRUE;

// Universal Modules
require_once( __CRYPT__ );           // Should precede SQL module
require_once( __SQL__ );             // Should precede Sessions module
require_once( __SESSIONS__ );        // Requires SQL module prior
require_once( __PHPUNIT__ );         // Requires SQL module prior
?>
