<?php

namespace Anax\Route;

use PHPUnit\Framework\TestCase;
use Anax\Route\Exception\ConfigurationException;

/**
 * Test configuration of the router, when failing.
 */
class RouterConfigurationFailTest extends TestCase
{
    /**
     * Configuration item is not an array.
     *
     * @expectedException \TypeError
     */
    public function testConfigurationIsNotAnArray()
    {
        $router = new Router();
        $router->addRoutes(1);
    }



    /**
     * Missing key "routes" throws exception.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testMissingRoute()
    {
        $router = new Router();
        $router->addRoutes([]);
    }



    /**
     * The route is not an array.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testRouteIsNotAnArray()
    {
        $router = new Router();
        $router->addRoutes(["routes" => 1]);
    }
}
