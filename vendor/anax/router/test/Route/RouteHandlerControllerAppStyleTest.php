<?php

namespace Anax\Route;

use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Try controller handlers using app style.
 */
class RouteHandlerControllerAppStyleTest extends TestCase
{
    /**
     * A handler can be a controller class with method indexAction.
     */
    public function testMethodIndexAction()
    {
        $route = new Route();

        $route->set(null, null, null, "Anax\Route\MockHandlerAppController");
        $this->assertTrue($route->match(""));
        $res = $route->handle("");
        $this->assertEquals("indexAction", $res);
    }



    /**
     * Call appAction that verifies that the $app is injected.
     */
    public function testMethodAppAction()
    {
        $route = new Route();

        $di = new DIMagic();
        $app = $di;
        $di->set("app", $app);

        $route->set(null, null, null, "Anax\Route\MockHandlerAppController");
        $this->assertTrue($route->match("app"));
        $res = $route->handle("app", $di);
        $this->assertEquals($app, $res);
    }
}
