<?php

namespace Anax\Route;

use Anax\Route\Exception\ForbiddenException;
use Anax\Route\Exception\InternalErrorException;
use Anax\Route\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Testcases.
 */
class DocumentationReadmeTest extends TestCase
{
    // /**
    //  * Test Add some routes with handlers
    //  */
    // public function testAddSomeRoutesWithHandles()
    // {
    //     $router = new Router();
    //
    //     $router->add("", function () {
    //         echo "home ";
    //     });
    //
    //     $router->add("about", function () {
    //         echo "about ";
    //     });
    //
    //     $router->add("about/me", function () {
    //         echo "about/me ";
    //     });
    //
    //     ob_start();
    //     // try it out
    //     $router->handle("");
    //     $router->handle("about");
    //     $router->handle("about/me");
    //     // home about about/me
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "home about about/me ");
    // }



    // /**
    //  * Test Add multiple routes with one handler
    //  */
    // public function testAddMultipleRoutesWithOneHandler()
    // {
    //     $router = new Router();
    //
    //     $router->add(["info", "about"], function () {
    //         echo "info or about - ";
    //     });
    //
    //     ob_start();
    //     // try it out
    //     $router->handle("info");
    //     $router->handle("about");
    //     // info or about - info or about -
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "info or about - info or about - ");
    // }



    // /**
    //  * Test Add a default route
    //  */
    // public function testAddDefaultRoute()
    // {
    //     $router = new Router();
    //
    //     $router->always(function () {
    //         echo "always ";
    //     });
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("info");
    //     $router->handle("about");
    //     // always always
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "always always ");
    // }



    /**
     * Test Add internal routes for 404, 403 and 500 error handling
     */
    public function testAddInternalRoutesForErrorHandling()
    {
        $router = new Router();

        $router->addInternalRoute("404", function () {
            echo "404 ";
        });

        $router->add("about", function () {
            echo "about ";
        });

        ob_start();
        // try it out using some paths
        $router->handle("whatever");
        // 404
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($res, "404 ");
    }



    /**
     * Test Add internal routes for 404, 403 and 500 error handling
     */
    public function testAddInternalRoute403()
    {
        $router = new Router();

        $router->addInternalRoute("403", function () {
            echo "403 ";
        });

        $router->add("login", function () {
            throw new ForbiddenException();
        });

        ob_start();
        // try it out using some paths
        $router->handle("login");
        // 403
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($res, "403 ");
    }



    /**
     * Test Add internal routes for 404, 403 and 500 error handling
     */
    public function testAddInternalRoute500()
    {
        $router = new Router();

        $router->addInternalRoute("500", function () {
            echo "500 ";
        });

        $router->add("calculate", function () {
            throw new InternalErrorException();
        });

        ob_start();
        // try it out using some paths
        $router->handle("calculate");
        // 500
        $res = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($res, "500 ");
    }



    // /**
    //  * Test Add a common route for any item below subpath using *
    //  */
    // public function testAddCommonRouteForSubPathOneStar()
    // {
    //     $router = new Router();
    //
    //     $router->addInternalRoute("404", function () {
    //         echo "404 ";
    //     });
    //
    //     $router->add("about/*", function () {
    //         echo "about ";
    //     });
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("about");
    //     $router->handle("about/me");
    //     $router->handle("about/you");
    //     $router->handle("about/some/other");
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "about about about 404 ");
    // }



    // /**
    //  * Test Add a common route for any item below subpath using **
    //  */
    // public function testAddCommonRouteForSubPathDoubleStar()
    // {
    //     $router = new Router();
    //
    //     $router->addInternalRoute("404", function () {
    //         echo "404 ";
    //     });
    //
    //     $router->add("about/**", function () {
    //         echo "about ";
    //     });
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("about");
    //     $router->handle("about/me");
    //     $router->handle("about/you");
    //     $router->handle("about/some/other");
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "about about about about ");
    // }



    // /**
    //  * Test Part of path as arguments to the route handler
    //  */
    // public function testRouteWithArguments()
    // {
    //     $router = new Router();
    //
    //     $router->addInternalRoute("404", function () {
    //         echo "404 ";
    //     });
    //
    //     $router->add("about/{arg}", function ($arg) {
    //         echo "$arg ";
    //     });
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("about");            // not matched
    //     $router->handle("about/me");
    //     $router->handle("about/you");
    //     $router->handle("about/some/other"); // not matched
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "404 me you 404 ");
    // }



    // /**
    //  * Test Part of path as arguments to the route handler
    //  */
    // public function testRouteWithMultipleArguments()
    // {
    //     $router = new Router();
    //
    //     $router->add(
    //         "post/{year}/{month}/{day}",
    //         function ($year, $month, $day) {
    //             echo "$year-$month-$day, ";
    //         }
    //     );
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("post/2017/03/07");
    //     $router->handle("post/1990/06/20");
    //     // 2017-03-07, 1990-06-20,
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "2017-03-07, 1990-06-20, ");
    // }



    // /**
    //  * Test Type checking of arguments
    //  */
    // public function testTypeCheckingOfArguments()
    // {
    //     $router = new Router();
    //
    //     $router->addInternalRoute("404", function () {
    //         echo "404, ";
    //     });
    //
    //     $router->add(
    //         "post/{year:digit}/{month:digit}/{day:digit}",
    //         function ($year, $month, $day) {
    //             echo "$year-$month-$day, ";
    //         }
    //     );
    //
    //     $router->add(
    //         "post/{year:digit}/{month:alpha}/{day:digit}",
    //         function ($year, $month, $day) {
    //             echo "$day $month $year, ";
    //         }
    //     );
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("post/2017/03/seven");
    //     $router->handle("post/2017/03/07");
    //     $router->handle("post/1990/06/20");
    //     $router->handle("post/1990/june/20");
    //     // 404, 2017-03-07, 1990-06-20, 20 june 1990,
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "404, 2017-3-7, 1990-6-20, 20 june 1990, ");
    // }



    // /**
    //  * Test Routes per request method
    //  */
    // public function testRoutePerRequestMethod()
    // {
    //     $router = new Router();
    //
    //     $router->any(["GET"], "about", function () {
    //         echo "GET ";
    //     });
    //
    //     $router->any(["POST"], "about", function () {
    //         echo "POST ";
    //     });
    //
    //     $router->any(["PUT"], "about", function () {
    //         echo "PUT ";
    //     });
    //
    //     $router->any(["DELETE"], "about", function () {
    //         echo "DELETE ";
    //     });
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("about", "GET");
    //     $router->handle("about", "POST");
    //     $router->handle("about", "PUT");
    //     $router->handle("about", "DELETE");
    //     // GET POST PUT DELETE
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "GET POST PUT DELETE ");
    // }



    // /**
    //  * Test Routes per request method
    //  */
    // public function testRoutePerMultipleRequestMethod()
    // {
    //     $router = new Router();
    //
    //     $router->any(["GET", "POST"], "about", function () {
    //         echo "GET+POST ";
    //     });
    //
    //     $router->any("PUT | DELETE", "about", function () {
    //         echo "PUT+DELETE ";
    //     });
    //
    //     ob_start();
    //     // try it out using some paths
    //     $router->handle("about", "GET");
    //     $router->handle("about", "POST");
    //     $router->handle("about", "PUT");
    //     $router->handle("about", "DELETE");
    //     // GET+POST GET+POST PUT+DELETE PUT+DELETE
    //     $res = ob_get_contents();
    //     ob_end_clean();
    //     $this->assertEquals($res, "GET+POST GET+POST PUT+DELETE PUT+DELETE ");
    // }
}
