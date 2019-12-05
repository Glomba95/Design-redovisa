<?php

namespace Anax\Route;

use Anax\Route\Exception\ConfigurationException;
use PHPUnit\Framework\TestCase;

/**
 * Try various type of handlers, negative tests.
 */
class RouteHandlerFailTest extends TestCase
{
    /**
     * A route mest evaluate to a callable or null.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testHandlerIsNotCallableNorNull()
    {
        $route = new Route();

        $route->set(null, null, null, "no handler");
        $route->handle();
    }
}
