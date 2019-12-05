<?php

namespace Anax\Route;

use Anax\Route\Exception\ConfigurationException;
use Anax\Route\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Try controller handlers that fails.
 */
class RouteHandlerControllerFailTest extends TestCase
{
    /**
     * Too few arguments.
     *
     * @expectedException Anax\Route\Exception\NotFoundException
     */
    public function testToFewArguments()
    {
        $route = new Route();
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");
        $path = "user/view";
        $this->assertTrue($route->match($path, "GET"));
        $route->handle($path);
    }



    /**
     * Too many arguments.
     *
     * @expectedException Anax\Route\Exception\NotFoundException
     */
    public function testToManyArguments()
    {
        $route = new Route();
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");
        $path = "user/view/1/1";
        $this->assertTrue($route->match($path, "GET"));
        $route->handle($path);
    }



    /**
     * Typed arguments as integer.
     *
     * @expectedException Anax\Route\Exception\NotFoundException
     */
    public function testTypedArgumentsInteger()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");

        $path = "user/view/a";
        $this->assertTrue($route->match($path, "GET"));
        $route->handle($path);
    }



    /**
     * Controller action is not a public method.
     *
     * @expectedException Anax\Route\Exception\NotFoundException
     */
    public function testControllerActionAsPrivateMethod()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");

        $path = "user/private";
        $this->assertTrue($route->match($path, "GET"));
        $route->handle($path);
    }



    /**
     * Try a user controller where the called action does not exists.
     *
     * @expectedException Anax\Route\Exception\ConfigurationException
     */
    public function testUserControllerActionDoesNotExists()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");
    
        $path = "user/no-exists";
        $this->assertTrue($route->match($path, "GET"));
        $route->handle($path);
    }
}
