<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try routes loaded in $di configuration.
 */
class DiServiceRoutesDefaultTest extends TestCase
{
    /**
     * Default configuration loading.
     */
    public function testConfiguration()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di_empty_router.php");
    
        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router.php");

        $res = $router->getAll();
        $this->assertEquals(0, count($res));
    }
}
