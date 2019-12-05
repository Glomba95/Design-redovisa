<?php

namespace Anax\Route;

use PHPUnit\Framework\TestCase;

/**
 * The Router class.
 */
class RouterTest extends TestCase
{
    /**
     * Check that the route can return a value.
     */
    public function testRouter()
    {
        $router = new Router();

        $router->add("about", function () {
            return "about";
        });

        $router->add("about/me", function () {
            return "about/me";
        });

        $res = $router->handle("about");
        $this->assertEquals("about", $res);

        $res = $router->handle("about/me");
        $this->assertEquals("about/me", $res);
    }



    /**
     * Check that all routes matching are called.
     */
    public function testRouterWithSeveralMatches()
    {
        $router = new Router();

        $router->add("", function () {
            echo "1";
        });

        $router->add("", function () {
            echo "2";
            return true;
        });

        ob_start();
        $res = $router->handle("");
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals("12", $res);
    }



    /**
     * Check that first matched route returning a value
     * is the returning value.
     */
    public function testReturnValueWithSeveralMatches()
    {
        $router = new Router();

        $router->add("about", function () {
            ;
        });

        $router->add("about", function () {
            return false;
        });

        $router->add("about", function () {
            return "yes";
        });

        $router->add("about", function () {
            return "no";
        });

        $res = $router->handle("about");
        $this->assertEquals("yes", $res);
    }



    /**
     * Check that "*" matches any route.
     */
    public function testRouterOneStar()
    {
        $router = new Router();

        $router->addInternalRoute("404", function () {
            echo "404 ";
        });

        $router->add("about/*", function () {
            echo "about ";
            return true;
        });

        ob_start();
        // try it out using some paths
        $router->handle("");
        $router->handle("about");
        $router->handle("about/me");
        $router->handle("about/you");
        $router->handle("about/some/other");
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($res, "404 about about about 404 ");
    }



    /**
     * Check that "**" matches any route, including subpaths.
     */
    public function testRouterDoubleStar()
    {
        $router = new Router();

        $router->addInternalRoute("404", function () {
            echo "404 ";
        });

        $router->add("about/**", function () {
            echo "about ";
            return 1;
        });

        ob_start();
        // try it out using some paths
        $router->handle("");
        $router->handle("about");
        $router->handle("about/me");
        $router->handle("about/you");
        $router->handle("about/some/other");
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($res, "404 about about about about ");
    }



    /**
     * Check that the any route matches any/all routes.
     */
    public function testRouterForAll()
    {
        $router = new Router();

        $router->all(null, function () {
            return "all";
        });
        $res = $router->handle("some/route");
        $this->assertEquals("all", $res);
    }



    /**
     * Provider of data to create routes.
     */
    public function routeDataProvider()
    {
        return [
            [
                null,
                "",
                "hit",
                "",
                ""
            ],
            [
                null,
                "index",
                "index",
                "index",
                "index"
            ],
            [
                "mount",
                "index/here",
                "index/here",
                "mount/index/here",
                "index/here"
            ],
        ];
    }



    /**
     * Get the matched part of the query.
     *
     * @dataProvider routeDataProvider
     */
    public function testGetMatchedPath($mount, $path, $resp, $query, $match)
    {
        $router = new Router();

        $router->addRoute(null, $mount, $path, function () use ($resp) {
            return $resp;
        });
        $res = $router->handle($query);
        $this->assertEquals($resp, $res);
        $res = $router->getMatchedPath();
        $this->assertEquals($match, $res);
        $res = $router->getLastRoute();
        $this->assertEquals($query, $res);
    }
}
