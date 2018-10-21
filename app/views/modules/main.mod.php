<?php
///////////////////////////////////////////////////////////////////////////////
// MealPlanner                             Penn State - Cohorts 19 & 20 @ 2018
///////////////////////////////////////////////////////////////////////////////
// Primary / Foundation Module
///////////////////////////////////////////////////////////////////////////////

// Constants
define( '__SITENAME__' , 'MealPlanner - Capstone Project' );
define( '__REDIRECT__' , '/Account/dashboard/' );
define( '__COPYRITE__' , '2018 © Penn State - Cohort 19 &amp; 20 - Group 8.' );

// Modules
define( '__HEADER__' , __DIR__.'/header.mod.php');
define( '__FOOTER__' , __DIR__.'/footer.mod.php');
define( '__NAVBAR__' , __DIR__.'/navbar.mod.php');
define( '__SPANEL__' , __DIR__.'/spanel.mod.php');
define( '__SIDEBAR__', __DIR__.'/sidebar.mod.php');
// Non-HTML modules
define( '__SQL__'    , __DIR__.'/sql.mod.php');
define( '__CRYPT__'  , __DIR__.'/crypt.mod.php');
define( '__SESSIONS__',__DIR__.'/sessions.mod.php');
define( '__PHPUNIT__', __DIR__.'/phpunit.mod.php');

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
