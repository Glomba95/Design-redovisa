<?php

use Anax\DI\DIFactoryConfig;

/**
 * Bootstrap the framework and handle the request.
 */

// Were are all the files?
define("ANAX_INSTALL_PATH", realpath(__DIR__ . "/.."));
//define("ANAX_APP_PATH", ANAX_INSTALL_PATH);

// Include essentials
require ANAX_INSTALL_PATH . "/config/commons.php";

// Get the autoloader by using composers version.
require ANAX_INSTALL_PATH . "/vendor/autoload.php";

// Add all framework services to $di
$di = new DIFactoryConfig();
$di->loadServices(ANAX_INSTALL_PATH . "/config/di");

// // Add anax/proxy access to $id, if available
// if (class_exists("\Anax\Proxy\ProxyDIFactory")) {
//     \Anax\Proxy\ProxyDIFactory::init($di);
// }

// Enable to also use $app style to access services
// $di = new DIMagic();
// $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
// $app = $di;

// Include user defined routes using programming-style.
foreach (glob(ANAX_INSTALL_PATH . "/route/*.php") as $route) {
    require $route;
}

// Leave to router to match incoming request to routes
$response = $di->get("router")->handle(
    $di->get("request")->getRoute(),
    $di->get("request")->getMethod()
);

// Send the HTTP response with headers and body
$di->get("response")->send($response);
