<?php

/**
 * Sample configuration file for Anax webroot.
 */


/**
 * Define essential Anax paths, end with /
 */
define("ANAX_INSTALL_PATH", realpath(__DIR__ . "/.."));
//define("ANAX_APP_PATH", ANAX_INSTALL_PATH . "/app");

define("ANAX_WITH_SESSION", false);



/**
 * Include autoloader.
 */
require ANAX_INSTALL_PATH . "/vendor/autoload.php";
