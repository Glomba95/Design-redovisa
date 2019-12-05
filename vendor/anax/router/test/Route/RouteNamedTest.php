<?php

namespace Anax\Route;

use \PHPUnit\Framework\TestCase;

/**
 * Test named routes.
 */
class RouteNamedTest extends TestCase
{
    /**
     * A route can have a name.
     */
    public function testSetRouteName()
    {
        $route = new Route();

        $route->set(null, null, null, null, "Info");
        $route->setName("name");

        $res = $route->getInfo();
        $this->assertEquals("Info", $res);
    }
}
