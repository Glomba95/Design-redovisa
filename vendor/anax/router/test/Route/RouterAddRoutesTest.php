<?php

namespace Anax\Route;

use \PHPUnit\Framework\TestCase;

/**
 * Various ways of adding routes.
 */
class RouterAddRoutesTest extends TestCase
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
     * Add a route using any() regardless of the http request method.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByAny($method)
    {
        $router = new Router();

        $router->any(null, "about", function () {
            return "about";
        });

        $res = $router->handle("about", $method);
        $this->assertEquals("about", $res);

        $routes = $router->getAll();
        $this->assertEquals(1, count($routes));
    }



    /**
     * Add a route using add() regardless of the http request method.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByAdd($method)
    {
        $router = new Router();

        $router->add("about", function () {
            return "about";
        });

        $res = $router->handle("about", $method);
        $this->assertEquals("about", $res);

        $routes = $router->getAll();
        $this->assertEquals(1, count($routes));
    }



    /**
     * Add a route using always() that match all paths.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByAlways($method)
    {
        $router = new Router();

        $router->always(function () {
            return "always";
        });

        $res = $router->handle("", $method);
        $this->assertEquals("always", $res);

        $res = $router->handle("about", $method);
        $this->assertEquals("always", $res);

        $res = $router->handle("what/ever", $method);
        $this->assertEquals("always", $res);

        $routes = $router->getAll();
        $this->assertEquals(1, count($routes));
    }



    /**
     * Add a route using all() that match all paths when the request
     * method matches.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByAll($method)
    {
        $router = new Router();

        $router->all($method, function () use ($method) {
            return "all-$method";
        });

        $res = $router->handle("", $method);
        $this->assertEquals("all-$method", $res);

        $res = $router->handle("about", $method);
        $this->assertEquals("all-$method", $res);

        $res = $router->handle("what/ever", $method);
        $this->assertEquals("all-$method", $res);

        $routes = $router->getAll();
        $this->assertEquals(1, count($routes));
    }



    /**
     * Add a route with a controller as callback.
     */
    public function testRouterAddController()
    {
        $router = new Router();

        $router->addController(null, "Anax\Route\MockHandlerController");

        $res = $router->handle("create");
        $this->assertEquals("createAction", $res);

        $routes = $router->getAll();
        $this->assertEquals(1, count($routes));
    }



    /**
     * Add a route using get() that match paths on request method GET.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByGet($method)
    {
        $router = new Router();

        $router->get("", function () {
            return "get";
        });

        $router->always(function () {
            return "not get";
        });

        $res = $router->handle("", $method);
        if ($method === "GET") {
            $this->assertEquals("get", $res);
        }
        if ($method !== "GET") {
            $this->assertEquals("not get", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add a route using post() that match paths on request method POST.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByPost($method)
    {
        $router = new Router();

        $router->post("", function () {
            return "post";
        });

        $router->always(function () {
            return "not post";
        });

        $res = $router->handle("", $method);
        if ($method === "POST") {
            $this->assertEquals("post", $res);
        }
        if ($method !== "POST") {
            $this->assertEquals("not post", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add a route using put() that match paths on request method PUT.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByPut($method)
    {
        $router = new Router();

        $router->put("", function () {
            return "put";
        });

        $router->always(function () {
            return "not put";
        });

        $res = $router->handle("", $method);
        if ($method === "PUT") {
            $this->assertEquals("put", $res);
        }
        if ($method !== "PUT") {
            $this->assertEquals("not put", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add a route using patch() that match paths on request method PATCH.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByPatch($method)
    {
        $router = new Router();

        $router->patch("", function () {
            return "patch";
        });

        $router->always(function () {
            return "not patch";
        });

        $res = $router->handle("", $method);
        if ($method === "PATCH") {
            $this->assertEquals("patch", $res);
        }
        if ($method !== "PATCH") {
            $this->assertEquals("not patch", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add a route using delete() that match paths on request method DELETE.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByDelete($method)
    {
        $router = new Router();

        $router->delete("", function () {
            return "delete";
        });

        $router->always(function () {
            return "not delete";
        });

        $res = $router->handle("", $method);
        if ($method === "DELETE") {
            $this->assertEquals("delete", $res);
        }
        if ($method !== "DELETE") {
            $this->assertEquals("not delete", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add a route using options() that match paths on request method OPTIONS.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByOptions($method)
    {
        $router = new Router();

        $router->options("", function () {
            return "options";
        });

        $router->always(function () {
            return "not options";
        });

        $res = $router->handle("", $method);
        if ($method === "OPTIONS") {
            $this->assertEquals("options", $res);
        }
        if ($method !== "OPTIONS") {
            $this->assertEquals("not options", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add two routes with the same handler.
     */
    public function testRouterAddByAddTwoRoutesOneHandler()
    {
        $router = new Router();

        $router->add(["", "about"], function () {
            return "yes";
        });

        $res = $router->handle("");
        $this->assertEquals("yes", $res);

        $last = $router->getLastRoute();
        $this->assertEquals("", $last);

        $res = $router->handle("about");
        $this->assertEquals("yes", $res);

        $last = $router->getLastRoute();
        $this->assertEquals("about", $last);

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }



    /**
     * Add one handler for one path and two request methods.
     *
     * @dataProvider httpMethodsProvider
     */
    public function testRouterAddByAddRouteSeveralMethods($method)
    {
        $router = new Router();

        $router->any(["GET", "POST"], "user", function () {
            return "user";
        });

        $router->always(function () {
            return "always";
        });

        $res = $router->handle("user", $method);
        if ($method === "GET" || $method === "POST") {
            $this->assertEquals("user", $res);
        }

        if (!($method === "GET" || $method === "POST")) {
            $this->assertEquals("always", $res);
        }

        $routes = $router->getAll();
        $this->assertEquals(2, count($routes));
    }
}
