<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try routes loaded in $di configuration.
 */
class DiServiceRoutes900Test extends TestCase
{
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
    public function testInternalRoutes($path, $res1, $res2)
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di_empty_router.php");
    
        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/900_internal.php");

        $res = $router->handleInternal($path);
        $this->assertEquals(2, count($res));
        $this->assertEquals($res1, $res[0]);
        $this->assertEquals($res2, $res[1]);
    }
}
