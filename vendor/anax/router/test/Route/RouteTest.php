<?php

namespace Anax\Route;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Routes.
 */
class RouteTest extends TestCase
{
    /**
     * Provider http methods.
     */
    public function httpMethodsProvider()
    {
        return [
            ["GET"],
            ["POST"],
            ["PUT"],
            ["PATCH"],
            ["DELETE"],
            ["OPTIONS"],
        ];
    }



    /**
     * Test
     */
    public function testHomeRoute()
    {
        $route = new Route();
        
        $route->set(null, null, "", null);
        $this->assertTrue($route->match(""));
        $this->assertFalse($route->match("-"));
    }



    /**
     * Test
     */
    public function testDefaultRoute()
    {
        $route = new Route();

        $route->set(null, null, "*", null);
        $this->assertTrue($route->match(""));
        $this->assertTrue($route->match("controller"));
        $this->assertFalse($route->match("controller/action"));
    }



    /**
     * Test
     */
    public function testGeneralRoute()
    {
        $route = new Route();
        
        $route->set(null, null, "doc", null);
        $this->assertFalse($route->match("doc/index"));
        $this->assertFalse($route->match("doc/index2"));
        $this->assertTrue($route->match("doc"));
        $this->assertFalse($route->match("do"));
        $this->assertFalse($route->match("docs"));
        $this->assertTrue($route->match("doc"));

        $route->set(null, null, "doc/index", null);
        $this->assertFalse($route->match("doc"));
        $this->assertFalse($route->match("doc/inde"));
        $this->assertFalse($route->match("doc/indexx"));
        $this->assertTrue($route->match("doc/index"));
    }



    /**
     * Test
     */
    public function testStarRoute()
    {
        $route = new Route();

        $route->set(null, null, "doc/*", null);
        $this->assertFalse($route->match("docs"));
        $this->assertTrue($route->match("doc"));
        $this->assertTrue($route->match("doc/"));
        $this->assertTrue($route->match("doc/index"));
        $this->assertFalse($route->match("doc/index/index"));

        $route->set(null, null, "doc/*/index", null);
        $this->assertFalse($route->match("doc"));
        $this->assertFalse($route->match("doc/index"));
        $this->assertFalse($route->match("doc/index/index1"));
        $this->assertTrue($route->match("doc/index/index"));
        $this->assertFalse($route->match("doc/index/index/index"));
    }



    /**
     * Test
     */
    public function testDoubleStarRoute()
    {
        $route = new Route();

        $route->set(null, null, "doc/**", null);
        $this->assertFalse($route->match("docs"));
        $this->assertTrue($route->match("doc"));
        $this->assertTrue($route->match("doc/"));
        $this->assertTrue($route->match("doc/index"));
        $this->assertTrue($route->match("doc/index/index"));

        $route->set(null, null, "doc/**/index", null);
        $this->assertFalse($route->match("docs"));
        $this->assertTrue($route->match("doc"));
        $this->assertTrue($route->match("doc/index"));
        $this->assertTrue($route->match("doc/index/index1"));
        $this->assertTrue($route->match("doc/index/index"));
        $this->assertTrue($route->match("doc/index/index/index"));
    }



    /**
     * Try all request methods without requirements on method.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRequestMethodNoRequirements($method)
    {
        $route = new Route();

        $route->set(null, null, "");
        $this->assertTrue($route->match(""));
        $this->assertTrue($route->match("", $method));
        $this->assertTrue($route->match("", "WHATEVER"));
    }



    /**
     * Try all request methods with requirements on method.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRequestMethodWithRequirements($method)
    {
        $route = new Route();

        $route->set($method, null, "");
        $this->assertFalse($route->match(""));
        $this->assertTrue($route->match("", $method));
        $this->assertFalse($route->match("", "WHATEVER"));
    }



    /**
     * Test
     */
    public function testSettingRequestMethod()
    {
        $route = new Route();

        $route->set(null, null, "");
        $this->assertTrue($route->match(""));

        $route->set("GET", null, "");
        $this->assertTrue($route->match("", "GET"));

        $route->set(["GET"], null, "");
        $this->assertTrue($route->match("", "GET"));

        $route->set(["GET", "POST"], null, "");
        $this->assertTrue($route->match("", "GET"));
        $this->assertTrue($route->match("", "POST"));
    }



    /**
     * Test
     */
    public function testAddMethodAsString()
    {
        $route = new Route();

        $route->set("GET|POST", null, "");
        $this->assertFalse($route->match(""));
        $this->assertTrue($route->match("", "GET"));
        $this->assertTrue($route->match("", "POST"));
        $this->assertFalse($route->match("", "PUT"));
        $this->assertFalse($route->match("", "DELETE"));

        $route->set("GET|POST | PUT | DEL", null, "");
        $this->assertFalse($route->match(""));
        $this->assertTrue($route->match("", "GET"));
        $this->assertTrue($route->match("", "POST"));
        $this->assertTrue($route->match("", "PUT"));
        $this->assertFalse($route->match("", "DELETE"));

        $route->set("GET|POST | PUT | DELETE", null, "");
        $this->assertFalse($route->match(""));
        $this->assertTrue($route->match("", "GET"));
        $this->assertTrue($route->match("", "POST"));
        $this->assertTrue($route->match("", "PUT"));
        $this->assertTrue($route->match("", "DELETE"));
    }



    /**
     * Test
     */
    public function testNullRoute()
    {
        $route = new Route();

        $route->set(null, null, null);
        $this->assertTrue($route->match("whatever/any"));
        $this->assertTrue($route->match("whatever/any", "GET"));
        $this->assertTrue($route->match("whatever/any", "POST"));
        $this->assertTrue($route->match("whatever/any", "PUT"));
        $this->assertTrue($route->match("whatever/any", "DELETE"));

        $route->set("GET|POST", null, null);
        $this->assertFalse($route->match("whatever/any"));
        $this->assertTrue($route->match("whatever/any", "GET"));
        $this->assertTrue($route->match("whatever/any", "POST"));
        $this->assertFalse($route->match("whatever/any", "PUT"));
        $this->assertFalse($route->match("whatever/any", "DELETE"));

        $route->set("GET | POST | PUT | DELETE", null, null);
        $this->assertFalse($route->match("whatever/any"));
        $this->assertTrue($route->match("whatever/any", "GET"));
        $this->assertTrue($route->match("whatever/any", "POST"));
        $this->assertTrue($route->match("whatever/any", "PUT"));
        $this->assertTrue($route->match("whatever/any", "DELETE"));
    }



    /**
     * Check that info can be stored with route.
     */
    public function testRouteWithInfo()
    {
        $route = new Route();

        $route->set(null, null, null, null, "Info");
        $res = $route->getInfo();
        $this->assertEquals("Info", $res);
    }



    /**
     * Get the route path.
     */
    public function testGetRoutePath()
    {
        $route = new Route();

        $route->set(null, "user", "create");
        $res = $route->getPath();
        $this->assertEquals("create", $res);
    }



    /**
     * Get the request method supported by a route.
     */
    public function testGetRequestMethod()
    {
        $route = new Route();

        $route->set("GET");
        $res = $route->getRequestMethod();
        $this->assertEquals("GET", $res);

        $route->set(["GET"]);
        $res = $route->getRequestMethod();
        $this->assertEquals("GET", $res);

        $route->set(["GET", "POST"]);
        $res = $route->getRequestMethod();
        $this->assertEquals("GET|POST", $res);
    }



    /**
     * Get the absolute route path.
     */
    public function testGetAbsoluteRoutePath()
    {
        $route = new Route();

        $route->set(null, null, null);
        $res = $route->getAbsolutePath();
        $this->assertNull($res);
    }



    /**
     * Get the handler type of the route.
     */
    public function testGetHandlerType()
    {
        $route = new Route();

        $route->set(null, null, null, null);
        $res = $route->getHandlerType();
        $this->assertEquals("null", $res);

        $route->set(null, null, null, function () {
            return "hi";
        });
        $res = $route->getHandlerType();
        $this->assertEquals("callable", $res);

        $route->set(null, null, null, ["\Anax\Route\MockHandlerClassMethod", "method"]);
        $res = $route->getHandlerType();
        $this->assertEquals("callable", $res);

        $route->set(null, null, null, ["\Anax\Route\MockHandlerClassMethod", "static"]);
        $res = $route->getHandlerType();
        $this->assertEquals("callable", $res);

        $route->set(null, null, null, "\Anax\Route\MockHandlerControllerCatchAll");
        $res = $route->getHandlerType();
        $this->assertEquals("controller", $res);

        $route->set(null, null, null, "not found");
        $res = $route->getHandlerType();
        $this->assertEquals("not found", $res);
    }



    /**
     * Get the handler type of the route, as di service.
     */
    public function testGetHandlerTypeAsDi()
    {
        $route = new Route();

        $service = new MockHandlerClassMethod();
        $di = new DIFactoryConfig();
        $di->set("service", $service);

        $route->set(null, null, null, ["service", "method"]);
        $res = $route->getHandlerType($di);
        $this->assertEquals("di", $res);

        $route->set(null, null, null, ["service", "static"]);
        $res = $route->getHandlerType($di);
        $this->assertEquals("di", $res);
    }
}
