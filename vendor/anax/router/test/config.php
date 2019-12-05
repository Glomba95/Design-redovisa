<?php

use Anax\DI\DIFactoryConfig;

/**
 * Configuration for tests.
 */


/**
 * Set the error reporting.
 */
error_reporting(-1);              // Report all type of errors
ini_set('display_errors', 1);     // Display all errors



/**
 * Define essential Anax paths, end with /
 */
define("ANAX_INSTALL_PATH", realpath(__DIR__ . "/.."));
//define("ANAX_APP_PATH", ANAX_INSTALL_PATH);



/**
 * Include autoloader.
 */
require ANAX_INSTALL_PATH . "/vendor/autoload.php";



/**
 * Include others.
 */
foreach (glob(__DIR__ . "/Mock/*.php") as $file) {
    require $file;
}



/**
 * Create and expose $di for testing route/files.php.
 */
$di = new DIFactoryConfig();
$di->loadServices(ANAX_INSTALL_PATH . "/test/config/di_empty_router.php");
