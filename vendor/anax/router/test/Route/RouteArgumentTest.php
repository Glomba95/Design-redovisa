<?php

namespace Anax\Route;

use \PHPUnit\Framework\TestCase;

/**
 * Routes.
 */
class RouteArgumentTest extends TestCase
{
    /**
     * Match route with one argument
     */
    public function testRouteWithOneArgument()
    {
        $route = new Route();

        $route->set(null, null, "search/{arg1}", function ($arg1) {
            return $arg1;
        });

        $this->assertFalse($route->match("search"));
        $this->assertFalse($route->match("search/1/2"));
        $this->assertFalse($route->match("search/1/2/3"));

        $this->assertTrue($route->match("search/1"));
        $this->assertEquals("1", $route->handle());
    }



    /**
     * Match route with two arguments
     */
    public function testRouteWithTwoArguments()
    {
        $route = new Route();

        $route->set(null, null, "search/{arg1}/{arg2}", function ($arg1, $arg2) {
            return "$arg1$arg2";
        });

        $this->assertFalse($route->match("search"));
        $this->assertFalse($route->match("search/1"));
        $this->assertFalse($route->match("search/1/2/3"));

        $this->assertTrue($route->match("search/1/2"));
        $this->assertEquals("12", $route->handle());
    }



    /**
     * Match route with two arguments, separated in path.
     */
    public function testRouteWithTwoArgumentsSeparated()
    {
        $route = new Route();

        $route->set(null, null, "search/{arg1}/what/{arg2}", function ($arg1, $arg2) {
            return "$arg1$arg2";
        });

        $this->assertFalse($route->match("search"));
        $this->assertFalse($route->match("search/1/2"));
        $this->assertFalse($route->match("search/1/what"));
        $this->assertFalse($route->match("search/1/what/2/3"));
        $this->assertFalse($route->match("search/1/2/3"));

        $this->assertTrue($route->match("search/1/what/2"));
        $this->assertEquals("12", $route->handle());
    }



    /**
     * Try argument validation.
     */
    public function testRouteArgumentValidate()
    {
        $route = new Route();
        $digit = "1234567890";
        $alpha = "abcdefghijklmnopqrstuvxyzABCDEFGHIJKLMNOPQRSTUVXYZ";
        $alphanum = $alpha . $digit;
        $hex = "abcdefABCDEF" . $digit;

        //
        $route->set(null, null, "{arg1:digit}", null);
        $this->assertFalse($route->match($alpha));
        $this->assertFalse($route->match($alphanum));
        $this->assertTrue($route->match($digit));
        $this->assertFalse($route->match($hex));

        //
        $route->set(null, null, "{arg1:alpha}", null);
        $this->assertTrue($route->match($alpha));
        $this->assertFalse($route->match($alphanum));
        $this->assertFalse($route->match($digit));
        $this->assertFalse($route->match($hex));

        //
        $route->set(null, null, "{arg1:alphanum}", null);
        $this->assertTrue($route->match($alpha));
        $this->assertTrue($route->match($alphanum));
        $this->assertTrue($route->match($digit));
        $this->assertTrue($route->match($hex));

        //
        $route->set(null, null, "{arg1:hex}", null);
        $this->assertFalse($route->match($alpha));
        $this->assertFalse($route->match($alphanum));
        $this->assertTrue($route->match($digit));
        $this->assertTrue($route->match($hex));

        // Missing/wrong index
        $route->set(null, null, "{arg1:none}", null);
        $this->assertFalse($route->match($alpha));
        $this->assertFalse($route->match($alphanum));
        $this->assertFalse($route->match($digit));
        $this->assertFalse($route->match($hex));
    }
}
