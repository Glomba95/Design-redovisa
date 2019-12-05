<?php

namespace Anax\Route;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Route\Exception\ConfigurationException;
use Psr\Container\ContainerInterface;

/**
 * Route to match a $path, mounted on $mount having a $handler to call.
 */
class Route
{
    /**
     * @var string       $name          a name for this route.
     * @var string       $info          description of route.
     * @var array        $method        the method(s) to support
     * @var string       $methodMatched the matched method.
     * @var string       $mount         where to mount the path
     * @var string       $path          the path rule for this route
     * @var string       $pathMatched   the matched path.
     * @var callable     $handler       the callback to handle this route
     * @var null|array   $arguments     arguments for the callback, extracted
     *                                  from path
     */
    private $name;
    private $info;
    private $method;
    private $methodMatched;
    private $mount;
    private $path;
    private $pathMatched;
    private $handler;
    private $arguments = [];



    /**
     * Set values for route.
     *
     * @param string|array           $method  as request method to support
     * @param string                 $mount   where to mount the path
     * @param string                 $path    for this route
     * @param string|array|callable  $handler for this path, callable or equal
     * @param string                 $info    description of the route
     *
     * @return $this
     */
    public function set(
        $method = null,
        $mount = null,
        $path = null,
        $handler = null,
        string $info = null
    ) : object {
        $this->mount = rtrim($mount, "/");
        $this->path = $path;
        $this->handler = $handler;
        $this->info = $info;

        $this->method = $method;
        if (is_string($method)) {
            $this->method = array_map("trim", explode("|", $method));
        }
        if (is_array($this->method)) {
            $this->method = array_map("strtoupper", $this->method);
        }

        return $this;
    }



    /**
     * Check if the route matches a query and request method.
     *
     * @param string $query  to match against
     * @param string $method as request method
     *
     * @return boolean true if query matches the route
     */
    public function match(string $query, string $method = null)
    {
        $this->arguments = [];
        $this->methodMatched = null;
        $this->pathMatched = null;

        $matcher = new RouteMatcher();
        $res = $matcher->match(
            $this->mount,
            $this->path,
            $this->getAbsolutePath(),
            $query,
            $this->method,
            $method
        );
        $this->arguments = $matcher->arguments;
        $this->methodMatched = $matcher->methodMatched;
        $this->pathMatched = $matcher->pathMatched;

        return $res;
    }



    /**
     * Handle the action for the route.
     *
     * @param string                       $path the matched path
     * @param ContainerInjectableInterface $di   container with services
     *
     * @return mixed
     */
    public function handle(
        string $path = null,
        ContainerInterface $di = null
    ) {
        if ($this->mount) {
            // Remove the mount path to get base for controller
            $len = strlen($this->mount);
            if (substr($path, 0, $len) == $this->mount) {
                $path = ltrim(substr($path, $len), "/");
            }
        }

        try {
            $handler = new RouteHandler();
            return $handler->handle($this->methodMatched, $path, $this->handler, $this->arguments, $di);
        } catch (ConfigurationException $e) {
            throw new ConfigurationException(
                $e->getMessage()
                . " Route matched method='{$this->methodMatched}', mount='{$this->mount}', path='$path', handler='"
                . (
                    is_string($this->handler)
                        ? "$this->handler"
                        : "array/object"
                )
                . "'."
            );
        }
    }



    /**
     * Set the path that matched this route.
     *
     * @param string $path to set
     *
     * @return $this
     */
    public function setMatchedPath($path)
    {
        $this->pathMatched = $path;
        return $this;
    }



    /**
     * Get the matched basename of the path, its the part without the mount
     * point.
     *
     * @return string|null
     */
    public function getMatchedPath()
    {
        $path = $this->pathMatched;
        if ($this->mount) {
            $len = strlen($this->mount);
            if (substr($path, 0, $len) == $this->mount) {
                $path = ltrim(substr($path, $len), "/");
            }
        }

        return $path;
    }



    /**
     * Set the name of the route.
     *
     * @param string $name set a name for the route
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }



    /**
     * Set the arguments of the route, these are sent to the route handler.
     *
     * @param array $args the arguments.
     *
     * @return $this
     */
    public function setArguments(array $args) : object
    {
        $this->arguments = $args;
        return $this;
    }



    /**
     * Get information of the route.
     *
     * @return null|string as route information.
     */
    public function getInfo()
    {
        return $this->info;
    }



    /**
     * Get the path for the route.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }



    /**
     * Get the absolute $path by adding $mount.
     *
     * @return string|null as absolute path for this route.
     */
    public function getAbsolutePath()
    {
        if (empty($this->mount) && is_null($this->path)) {
            return null;
        }

        if (empty($this->mount)) {
            return $this->path;
        }

        return $this->mount . "/" . $this->path;
    }



    /**
     * Get the request method for the route.
     *
     * @return string representing the request method supported
     */
    public function getRequestMethod() : string
    {
        return is_array($this->method)
            ? implode("|", $this->method)
            : "";
    }



    /**
     * Get the handler type as a informative string.
     *
     * @param ContainerInjectableInterface $di container with services
     *
     * @return string representing the handler.
     */
    public function getHandlerType(ContainerInterface $di = null) : string
    {
        $handler = new RouteHandler();
        return $handler->getHandlerType($this->handler, $di);
    }
}
