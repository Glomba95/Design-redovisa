<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use Anax\Route\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * Try $di handlers that fails.
 */
class RouteHandlerDiFailTest extends TestCase
{
    /**
     * A $di container.
     */
    private static $di;



    /**
     * Setup a fixture for all tests.
     */
    public static function setUpBeforeClass()
    {
        self::$di = new DIFactoryConfig();
        self::$di->loadServices([
           "services" => [
               "user" => [
                   "active" => false,
                   "shared" => true,
                   "callback" => function () {
                       $obj = new MockHandlerDiService();
                       return $obj;
                   }
               ],
           ],
        ]);
    }



    /**
     * No such service in $di.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testServiceDoesNotExists()
    {
        $route = new Route();

        $route->set(null, null, null, ["noservice", "index"]);
        $this->assertTrue($route->match(""));
        $route->handle("", self::$di);
    }



    /**
     * The service does not have that method.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testServiceWithNoMethod()
    {
        $route = new Route();

        $route->set(null, null, null, ["user", "nomethod"]);
        $this->assertTrue($route->match(""));
        $route->handle("", self::$di);
    }
}
