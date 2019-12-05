<?php
/**
 * Internal routes for error handling to show response when internal
 * exceptions are thrown.
 */
global $di;
$router = $di->get("router");

$router->addInternalRoute("403", function () {
    return ["Anax 403: Forbidden", 403];
}, "403 Forbidden.");

$router->addInternalRoute("404", function () {
    return ["Anax 404: Not Found", 404];
}, "404 Not Found.");

$router->addInternalRoute("500", function () {
    // echo "<pre>";
    // debug_print_backtrace();
    return ["Anax 500: Internal Server Error", 500];
}, "500 Internal Server Error.");
