<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try routes loaded in $di configuration.
 */
class DiServiceRoutes000Test extends TestCase
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

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/000_application.php");

        $res = $router->handle("");
        $this->assertEquals(2, count($res));
        $this->assertEquals("Anax: Just saying Hi!", $res[0]);
        $this->assertEquals(200, $res[1]);
    }
}
