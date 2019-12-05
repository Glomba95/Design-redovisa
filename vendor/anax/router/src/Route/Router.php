<?php

namespace Anax\Route;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Route\Exception\ConfigurationException;
use Anax\Route\Exception\ForbiddenException;
use Anax\Route\Exception\InternalErrorException;
use Anax\Route\Exception\NotFoundException;

/**
 * A router to hold and match routes.
 */
class Router implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var array  $routes         all the routes.
     * @var array  $internalRoutes all internal routes.
     * @var Route  $lastRoute       last route that was matched and called.
     * @var string $errorMessage    last error message for internal routes.
     */
    private $routes         = [];
    private $internalRoutes = [];
    private $lastRoute      = null;
    private $errorMessage = null;



    /**
     * @const DEVELOPMENT Verbose with exceptions.
     * @const PRODUCTION  Exceptions turns into 500.
     */
    const DEVELOPMENT = 0;
    const PRODUCTION  = 1;



    /**
     * @var integer $mode current mode.
     */
    private $mode = self::DEVELOPMENT;



    /**
     * Set Router::DEVELOPMENT or Router::PRODUCTION mode.
     *
     * @param integer $mode which mode to set.
     *
     * @return self to enable chaining.
     */
    public function setMode($mode) : object
    {
        $this->mode = $mode;
        return $this;
    }



    /**
     * Add routes from an array where the array looks like this:
     * [
     *      "mount" => null|string, // Where to mount the routes
     *      "routes" => [           // All routes in this array
     *          [
     *              "info" => "Just say hi.",
     *              "method" => null,
     *              "path" => "hi",
     *              "handler" => function () {
     *                  return "Hi.";
     *              },
     *          ]
     *      ]
     * ]
     *
     * @throws ConfigurationException
     *
     * @param array $routes containing the routes to add.
     *
     * @return self to enable chaining.
     */
    public function addRoutes(array $routes) : object
    {
        if (!(isset($routes["routes"]) && is_array($routes["routes"]))) {
            throw new ConfigurationException("No routes found, missing key 'routes' in configuration array.");
        }

        foreach ($routes["routes"] as $route) {
            if ($route["internal"] ?? false) {
                $this->addInternalRoute(
                    $route["path"] ?? null,
                    $route["handler"] ?? null,
                    $route["info"] ?? null
                );
                continue;
            }

            $mount = $this->createMountPath(
                $routes["mount"] ?? null,
                $route["mount"] ?? null
            );

            $this->addRoute(
                $route["method"] ?? null,
                $mount,
                $route["path"] ?? null,
                $route["handler"] ?? null,
                $route["info"] ?? null
            );
        }

        return $this;
    }



    /**
     * Prepare the mount string from configuration, use $mount1 or $mount2,
     * the latter supersedes the first.
     *
     * @param string $mount1 first suggestion to mount path.
     * @param string $mount2 second suggestion to mount path, ovverides
     *                       the first.
     *
     * @return string|null as mount path.
     */
    private function createMountPath(
        string $mount1 = null,
        string $mount2 = null
    ) {
        $mount = null;
        if ($mount1 && $mount2) {
            $mount = rtrim($mount1, "/") . "/" . rtrim($mount2, "/");
            return $mount;
        }

        if ($mount1) {
            $mount = $mount1;
        }

        if ($mount2) {
            $mount = $mount2;
        }

        trim($mount);
        rtrim($mount, "/");
        $mount = empty($mount) ? null : $mount;

        return $mount;
    }



    /**
     * Add a route with a request method, a path rule to match and an action
     * as the callback. Adding several path rules (array) results in several
     * routes being created.
     *
     * @param string|array           $method  as request method to support
     * @param string                 $mount   prefix to $path
     * @param string|array           $path    for this route, array for several
     *                                        paths
     * @param string|array|callable  $handler for this path, callable or equal
     * @param string                 $info    description of the route
     *
     * @return void.
     */
    public function addRoute(
        $method,
        $mount = null,
        $path = null,
        $handler = null,
        string $info = null
    ) : void {
        if (!is_array($path)) {
            $path = [$path];
        }

        foreach ($path as $thePath) {
            $route = new Route();
            $route->set($method, $mount, $thePath, $handler, $info);
            $this->routes[] = $route;
        }
    }



    /**
     * Add an internal route to the router, this route is not exposed to the
     * browser and the end user.
     *
     * @param string                 $path    for this route
     * @param string|array|callable  $handler for this path, callable or equal
     * @param string                 $info    description of the route
     *
     * @return void.
     */
    public function addInternalRoute(
        string $path = null,
        $handler,
        string $info = null
    ) : void {
        $route = new Route();
        $route->set(null, null, $path, $handler, $info);
        $this->internalRoutes[$path] = $route;
    }



    /**
     * Handle the routes and match them towards the request, dispatch them
     * when a match is made. Each route handler may throw exceptions that
     * may redirect to an internal route for error handling.
     * Several routes can match and if the routehandler does not break
     * execution flow, the route matching will carry on.
     * Only the last routehandler will get its return value returned further.
     *
     * @param string $path    the path to find a matching handler for.
     * @param string $method  the request method to match.
     *
     * @return mixed content returned from route.
     */
    public function handle($path, $method = null)
    {
        try {
            $match = false;
            foreach ($this->routes as $route) {
                if ($route->match($path, $method)) {
                    $this->lastRoute = $route;
                    $match = true;
                    $results = $route->handle($path, $this->di);
                    if ($results) {
                        return $results;
                    }
                }
            }

            return $this->handleInternal("404", "No route could be matched by the router.");
        } catch (ForbiddenException $e) {
            return $this->handleInternal("403", $e->getMessage());
        } catch (NotFoundException $e) {
            return $this->handleInternal("404", $e->getMessage());
        } catch (InternalErrorException $e) {
            return $this->handleInternal("500", $e->getMessage());
        } catch (\Exception $e) {
            if ($this->mode === Router::DEVELOPMENT) {
                throw $e;
            }
            return $this->handleInternal("500", $e->getMessage());
        }
    }



    /**
     * Handle an internal route, the internal routes are not exposed to the
     * end user.
     *
     * @param string $path    for this route.
     * @param string $message with additional details.
     *
     * @throws \Anax\Route\Exception\NotFoundException
     *
     * @return mixed from the route handler.
     */
    public function handleInternal(string $path, string $message = null)
    {
        $route = $this->internalRoutes[$path]
            ?? $this->internalRoutes[null]
            ?? null;

        if (!$route) {
            throw new NotFoundException("No internal route to handle: " . $path);
        }

        $this->errorMessage = $message;
        if ($message) {
            $route->setArguments([$message]);
        }

        $route->setMatchedPath($path);
        $this->lastRoute = $route;
        return $route->handle(null, $this->di);
    }



    /**
     * Add a route having a controller as a handler.
     *
     * @param string|array    $mount   point for this controller.
     * @param string|callable $handler a callback handler for the controller.
     * @param string          $info    description of the route.
     *
     * @return void.
     */
    public function addController($mount = null, $handler = null, $info = null)
    {
        $this->addRoute(null, $mount, null, $handler, $info);
    }



    /**
     * Add a route to the router by its method(s),  path(s) and a callback.
     *
     * @param string|array    $method  as request method to support
     * @param string|array    $path    for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void.
     */
    public function any($method = null, $path = null, $handler = null, $info = null)
    {
        $this->addRoute($method, null, $path, $handler, $info);
    }



    /**
     * Add a route to the router by its path(s) and a callback for any
     * request method .
     *
     * @param string|array    $path    for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function add($path = null, $handler = null, $info = null)
    {
        $this->addRoute(null, null, $path, $handler, $info);
    }



    /**
    * Add a default route which will be applied for any path and any
    * request method.
     *
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function always($handler, $info = null)
    {
        $this->addRoute(null, null, null, $handler, $info);
    }



    /**
     * Add a default route which will be applied for any path, if the choosen
     * request method is matching.
     *
     * @param string|array    $method  as request method to support
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function all($method, $handler, $info = null)
    {
        $this->addRoute($method, null, null, $handler, $info);
    }



    /**
     * Shortcut to add a GET route for the http request method GET.
     *
     * @param string|array    $path   for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function get($path, $handler, $info = null)
    {
        $this->addRoute(["GET"], null, $path, $handler, $info);
    }



    /**
     * Shortcut to add a POST route for the http request method POST.
     *
     * @param string|array    $path   for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function post($path, $handler, $info = null)
    {
        $this->addRoute(["POST"], null, $path, $handler, $info);
    }



    /**
     * Shortcut to add a PUT route for the http request method PUT.
     *
     * @param string|array    $path   for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function put($path, $handler, $info = null)
    {
        $this->addRoute(["PUT"], null, $path, $handler, $info);
    }



    /**
     * Shortcut to add a PATCH route for the http request method PATCH.
     *
     * @param string|array    $path   for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function patch($path, $handler, $info = null)
    {
        $this->addRoute(["PATCH"], null, $path, $handler, $info);
    }



    /**
     * Shortcut to add a DELETE route for the http request method DELETE.
     *
     * @param string|array    $path   for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function delete($path, $handler, $info = null)
    {
        $this->addRoute(["DELETE"], null, $path, $handler, $info);
    }



    /**
     * Shortcut to add a OPTIONS route for the http request method OPTIONS.
     *
     * @param string|array    $path   for this route.
     * @param string|callable $handler a callback handler for the route.
     * @param string          $info    description of the route
     *
     * @return void
     */
    public function options($path, $handler, $info = null)
    {
        $this->addRoute(["OPTIONS"], null, $path, $handler, $info);
    }



    /**
     * Get the route for the last route that was handled.
     *
     * @return mixed
     */
    public function getLastRoute()
    {
        return $this->lastRoute->getAbsolutePath();
    }



    /**
     * Get the route for the last route that was handled.
     *
     * @return mixed
     */
    public function getMatchedPath()
    {
        return $this->lastRoute->getMatchedPath();
    }



    /**
     * Get last error message supplied when handling internal routes.
     *
     * @return string as the error message, if any.
     */
    public function getErrorMessage() : ?string
    {
        return $this->errorMessage;
    }



    /**
     * Get all routes.
     *
     * @return array with all routes.
     */
    public function getAll()
    {
        return $this->routes;
    }



    /**
     * Get all internal routes.
     *
     * @return array with internal routes.
     */
    public function getInternal()
    {
        return $this->internalRoutes;
    }
}
