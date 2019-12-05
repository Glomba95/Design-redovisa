<?php

namespace Anax\Route;

use Anax\Configure\Configuration;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try routes loaded in $di configuration.
 */
class DiServiceRoutes600Test extends TestCase
{
    /**
     * A di/router containing only routes to be tested.
     */
    private $di;



    /**
     * Setup before each testcase.
     */
    protected function setUp()
    {
        $this->di = new DIFactoryConfig();
        $this->di->loadServices(ANAX_INSTALL_PATH . "/vendor/anax/router/test/config/di_empty_router.php");
    
        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("session", ANAX_INSTALL_PATH . "/vendor/anax/session/config/session.php");
        $this->di->set("configuration", $cfg);

        $this->di->loadServices(ANAX_INSTALL_PATH . "/vendor/anax/request/config/di");

        $this->di->loadServices(ANAX_INSTALL_PATH . "/vendor/anax/session/config/di");

        $router = $this->di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/600_development.php");
    }



    /**
     * Index route
     */
    public function testRouteIndex()
    {
        $router = $this->di->get("router");

        ob_start();
        $res = $router->handle("dev");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>Anax development utilities</h1>", $res);
    }



    /**
     * dev/di
     */
    public function testRouteDi()
    {
        $router = $this->di->get("router");

        ob_start();
        $res = $router->handle("dev/di");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>DI and services</h1>", $res);
    }



    /**
     * dev/request
     */
    public function testRouteRequest()
    {
        $router = $this->di->get("router");

        ob_start();
        $res = $router->handle("dev/request");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>Request</h1>", $res);
    }



    /**
     * dev/router
     */
    public function testRouteRouter()
    {
        $router = $this->di->get("router");

        $router->addInternalRoute("500", function () {
            return "500";
        });

        ob_start();
        $res = $router->handle("dev/router");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>Router</h1>", $res);
    }



    /**
     * dev/session
     */
    public function testRouteSession()
    {
        $router = $this->di->get("router");

        ob_start();
        $res = $router->handle("dev/session");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>Session</h1>", $res);
    }



    /**
     * dev/session/increment
     */
    public function testRouteSessionIncrement()
    {
        $router = $this->di->get("router");

        ob_start();
        $res = $router->handle("dev/session/increment");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>Session increment</h1>", $res);
    }



    /**
     * dev/session/destroy
     */
    public function testRouteSessionDestroy()
    {
        $router = $this->di->get("router");

        ob_start();
        $res = $router->handle("dev/session/destroy");
        $res .= ob_get_contents();
        ob_end_clean();

        $this->assertContains("<h1>Session destroy</h1>", $res);
    }
}
