<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Routes included from files, programming style.
 */
class RouterProgrammingStyleTest extends TestCase
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
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di_empty_router.php");

        // Include user defined routes using programming-style.
        foreach (glob(ANAX_INSTALL_PATH . "/router/*.php") as $route) {
            require $route;
        }

        self::$di = $di;
    }



    /**
     * Add router files (to get code coverage).
     */
    public function testLoadRoutes()
    {
        foreach (glob(ANAX_INSTALL_PATH . "/router/*.php") as $route) {
            require $route;
            $this->assertTrue(true);
        }
    }



    /**
     * Check that route can be added.
     */
    public function testRouteAdd()
    {
        $router = self::$di->get("router");
    
        $res = $router->handle("");
        $this->assertContains("index", $res);
    }



    /**
     * Try when route is not found.
     */
    public function testRouteNotFound()
    {
        $router = self::$di->get("router");
    
        $res = $router->handle("route/not/found");
        $this->assertEquals("Anax 404: Not Found", $res[0]);
        $this->assertEquals(404, $res[1]);
    }



    /**
     * Provider internal routes.
     */
    public function internalRoutesProvider()
    {
        return [
            ["403", "Anax 403: Forbidden", 403],
            ["404", "Anax 404: Not Found", 404],
            ["500", "Anax 500: Internal Server Error", 500],
        ];
    }

    /**
     * Check internal routes.
     *
     * @dataProvider internalRoutesProvider
     */
    public function testRouteInternal($path, $res1, $res2)
    {
        $router = self::$di->get("router");

        $res = $router->handleInternal($path);
        $this->assertEquals(2, count($res));
        $this->assertEquals($res1, $res[0]);
        $this->assertEquals($res2, $res[1]);
    }
}
