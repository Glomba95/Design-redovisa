<?php

namespace Anax\Route;

use Anax\DI\DI;
use PHPUnit\Framework\TestCase;

/**
 * Try controller handlers.
 */
class RouteHandlerControllerTest extends TestCase
{
    /**
     * A handler can be a controller class with method indexAction.
     */
    public function testMethodIndexAction()
    {
        $route = new Route();

        $route->set(null, null, null, "Anax\Route\MockHandlerController");
        $this->assertTrue($route->match(""));
        $res = $route->handle("");
        $this->assertEquals("indexAction", $res);

        $route->set(null, null, "", "Anax\Route\MockHandlerController");
        $this->assertTrue($route->match(""));
        $res = $route->handle("");
        $this->assertEquals("indexAction", $res);

        $route->set(null, "", null, "Anax\Route\MockHandlerController");
        $this->assertTrue($route->match(""));
        $res = $route->handle("");
        $this->assertEquals("indexAction", $res);
    }



    /**
     * Call indexActionGET.
     */
    public function testMethodIndexActionGET()
    {
        $route = new Route();

        $route->set(null, null, null, "Anax\Route\MockHandlerController");
        $this->assertTrue($route->match("", "GET"));
        $res = $route->handle("");
        $this->assertEquals("indexActionGET", $res);
    }


    /**
     * Call indexActionPOST.
     */
    public function testMethodIndexActionPOST()
    {
        $route = new Route();

        $route->set(null, null, null, "Anax\Route\MockHandlerController");
        $this->assertTrue($route->match("", "POST"));
        $res = $route->handle("");
        $this->assertEquals("indexActionPOST", $res);
    }



    /**
     * Mount a user controller and use it.
     */
    public function testUserControllerBasic()
    {
        $route = new Route();

        $route->set(null, "user", null, "Anax\Route\MockHandlerController");

        $path = "user";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("indexActionGET", $res);

        $path = "user/";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("indexActionGET", $res);

        $path = "user/create";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("createAction", $res);

        $path = "user/list";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("listAction", $res);
    }



    /**
     * Try a user controller with arguments.
     */
    public function testUserControllerWithArguments()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");

        $path = "user/view/1";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("viewAction id:1", $res);

        $path = "user/search/moped";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("searchAction str:moped", $res);

        $path = "user/test/42/moped";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("testAction id:42 str:moped", $res);
    }



    /**
     * Try typed arguments.
     */
    public function testUserControllerWithTypedArguments()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");

        $path = "user/view/1";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("viewAction id:1", $res);

        $path = "user/search/moped";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("searchAction str:moped", $res);

        $path = "user/test/42/moped";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("testAction id:42 str:moped", $res);
    }



    /**
     * Try a user controller with variadic arguments.
     */
    public function testUserControllerWithVariadicArguments()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");
    
        $path = "user/variadic";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("variadicAction collection:0", $res);

        $path = "user/variadic/1";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("variadicAction collection:1", $res);

        $path = "user/variadic/1/2/3/moped";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("variadicAction collection:4", $res);
    }



    /**
     * Try a user controller which can be injected with $di.
     */
    public function testControllerImplementsDiInterface()
    {
        $route = new Route();
        $di = new DI();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerController");
    
        $path = "user/di";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path, $di);
        $this->assertInstanceOf(DI::class, $res);
    }



    /**
     * A controller can implement a catchAll() method.
     */
    public function testControllerMethodCatchAll()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerControllerCatchAll");
    
        $path = "user/whatever";
        $this->assertTrue($route->match($path, "GET"));
        $res = $route->handle($path);
        $this->assertEquals("catchAll", $res);
    }



    /**
     * A controller can implement a catchAll() method that maps the the
     * request method.
     */
    public function testControllerMethodCatchAllRequestMethod()
    {
        $route = new Route();
    
        $route->set(null, "user", null, "Anax\Route\MockHandlerControllerCatchAll");
    
        $path = "user/whatever";
        $this->assertTrue($route->match($path, "POST"));
        $res = $route->handle($path);
        $this->assertEquals("catchAllPost", $res);

        $this->assertTrue($route->match($path, "PUT"));
        $res = $route->handle($path);
        $this->assertEquals("catchAllPut", $res);
    }
}
