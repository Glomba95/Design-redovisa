<?php
/**
 * Add routes to the router, through programming style, as an alternative
 * to the router configuration files.
 */
global $di;
$router = $di->get("router");

$router->add("", function() use ($di) {
    return "index";
}, "A sample index route.");
