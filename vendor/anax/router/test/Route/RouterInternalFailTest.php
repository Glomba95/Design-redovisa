<?php

namespace Anax\Route;

use PHPUnit\Framework\TestCase;
use Anax\Route\Exception\NotFoundException;

/**
 * Check when internal routes fail.
 */
class RouterInternalFailTest extends TestCase
{
    /**
     * When internal route is not found.
     *
     * @expectedException Anax\Route\Exception\NotFoundException
     */
    public function testInternalRouteIsMissing()
    {
        $router = new Router();
        $router->handleInternal("403");
    }
}
