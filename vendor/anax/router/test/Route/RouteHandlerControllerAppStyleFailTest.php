<?php

namespace Anax\Route;

use Anax\DI\DIMagic;
use PHPUnit\Framework\TestCase;

/**
 * Try controller handlers using app style, negative testing.
 */
class RouteHandlerControllerAppStyleFailTest extends TestCase
{
    /**
     * Call appAction but no $app in $di.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testMethodAppActionWithoutAppInjectedIntoDi()
    {
        $route = new Route();
        $di = new DIMagic();

        $route->set(null, null, null, "Anax\Route\MockHandlerAppController");
        $this->assertTrue($route->match("app"));
        $res = $route->handle("app", $di);
        $this->assertEquals($app, $res);
    }
}
