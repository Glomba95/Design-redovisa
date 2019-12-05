<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try $di handlers.
 */
class RouteHandlerDiTest extends TestCase
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
     * A handler can be a $di service with a method.
     */
    public function testMethodIndex()
    {
        $route = new Route();

        $route->set(null, null, null, ["user", "index"]);
        $this->assertTrue($route->match(""));
        $res = $route->handle("", self::$di);
        $this->assertEquals("index", $res);
    }
}
