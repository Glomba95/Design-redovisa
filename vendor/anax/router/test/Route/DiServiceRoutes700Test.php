<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try routes loaded in $di configuration.
 */
class DiServiceRoutes700Test extends TestCase
{
    /**
     * Default home route.
     */
    public function testRouteHome()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di_empty_router.php");
    
        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/700_example.php");

        $res = $router->handle("example/hi");
        $this->assertEquals("Hi.", $res);
    }
}
