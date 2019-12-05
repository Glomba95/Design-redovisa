<?php

namespace Anax\Route;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Route\Exception\ConfigurationException;
use Anax\Route\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Call a routes handler and return the results.
 */
class RouteHandler
{
    /**
     * @var ContainerInterface $di the dependency/service container.
     */
    protected $di;



    /**
     * Handle the action for a route and return the results.
     *
     * @param string                       $method    the request method.
     * @param string                       $path      that was matched.
     * @param string|array                 $action    base for the callable.
     * @param array                        $arguments optional arguments.
     * @param ContainerInjectableInterface $di        container with services.
     *
     * @return mixed as the result from the route handler.
     */
    public function handle(
        string $method = null,
        string $path = null,
        $action,
        array $arguments = [],
        ContainerInterface $di = null
    ) {
        $this->di = $di;

        if (is_null($action)) {
            return;
        }

        if (is_callable($action)) {
            if (is_array($action)
                && is_string($action[0])
                && class_exists($action[0])
            ) {
                $action[] = $arguments;
                return $this->handleAsControllerAction($action);
            }
            return $this->handleAsCallable($action, $arguments);
        }

        if (is_string($action) && class_exists($action)) {
            $callable = $this->isControllerAction($method, $path, $action);
            if ($callable) {
                return $this->handleAsControllerAction($callable);
            }
        }

        if ($di
            && is_array($action)
            && isset($action[0])
            && isset($action[1])
            && is_string($action[0])
        ) {
            // Try to load service from app/di injected container
            return $this->handleUsingDi($action, $arguments, $di);
        }
        
        throw new ConfigurationException("Handler for route does not seem to be a callable action.");
    }



    /**
     * Get  an informative string representing the handler type.
     *
     * @param string|array                 $action    base for the callable.
     * @param ContainerInjectableInterface $di        container with services.
     *
     * @return string as the type of handler.
     */
    public function getHandlerType(
        $action,
        ContainerInterface $di = null
    ) {
        if (is_null($action)) {
            return "null";
        }

        if (is_callable($action)) {
            return "callable";
        }

        if (is_string($action) && class_exists($action)) {
            $callable = $this->isControllerAction(null, null, $action);
            if ($callable) {
                return "controller";
            }
        }

        if ($di
            && is_array($action)
            && isset($action[0])
            && isset($action[1])
            && is_string($action[0])
            && $di->has($action[0])
            && is_callable([$di->get($action[0]), $action[1]])
        ) {
            return "di";
        }

        return "not found";
    }



    /**
     * Check if items can be used to call a controller action, verify
     * that the controller exists, the action has a class-method to call.
     *
     * @param string $method the request method.
     * @param string $path   the matched path, base for the controller action
     *                       and the arguments.
     * @param string $class  the controller class
     *
     * @return array with callable details.
     */
    protected function isControllerAction(
        string $method = null,
        string $path = null,
        string $class
    ) {
        $method = ucfirst(strtolower($method));
        $args = explode("/", $path);
        $action = array_shift($args);
        $action = empty($action) ? "index" : $action;
        $action = str_replace("-", "", $action);
        $action1 = "{$action}Action{$method}";
        $action2 = "{$action}Action";
        $action3 = "catchAll{$method}";
        $action4 = "catchAll";

        foreach ([$action1, $action2] as $target) {
            try {
                $refl = new \ReflectionMethod($class, $target);
                if (!$refl->isPublic()) {
                    throw new NotFoundException("Controller method '$class::$target' is not a public method.");
                }

                return [$class, $target, $args];
            } catch (\ReflectionException $e) {
                ;
            }
        }

        foreach ([$action3, $action4] as $target) {
            try {
                $refl = new \ReflectionMethod($class, $target);
                if (!$refl->isPublic()) {
                    throw new NotFoundException("Controller method '$class::$target' is not a public method.");
                }

                array_unshift($args, $action);
                return [$class, $target, $args];
            } catch (\ReflectionException $e) {
                ;
            }
        }

        return false;
    }



    /**
     * Call the controller action with optional arguments and call
     * initialisation methods if available.
     *
     * @param string $callable with details on what controller action to call.
     *
     * @return mixed result from the handler.
     */
    protected function handleAsControllerAction(array $callable)
    {
        $class = $callable[0];
        $action = $callable[1];
        $args = $callable[2];
        $obj = new $class();

        $refl = new \ReflectionClass($class);
        $diInterface = "Anax\Commons\ContainerInjectableInterface";
        $appInterface = "Anax\Commons\AppInjectableInterface";

        if ($this->di && $refl->implementsInterface($diInterface)) {
            $obj->setDI($this->di);
        } elseif ($this->di && $refl->implementsInterface($appInterface)) {
            if (!$this->di->has("app")) {
                throw new ConfigurationException(
                    "Controller '$class' implements AppInjectableInterface but \$app is not available in \$di."
                );
            }
            $obj->setApp($this->di->get("app"));
        }

        try {
            $refl = new \ReflectionMethod($class, "initialize");
            if ($refl->isPublic()) {
                $obj->initialize();
            }
        } catch (\ReflectionException $e) {
            ;
        }

        $refl = new \ReflectionMethod($obj, $action);
        $paramIsVariadic = false;
        foreach ($refl->getParameters() as $param) {
            if ($param->isVariadic()) {
                $paramIsVariadic = true;
                break;
            }
        }

        if (!$paramIsVariadic
            && $refl->getNumberOfParameters() < count($args)
        ) {
            throw new NotFoundException(
                "Controller '$class' with action method '$action' valid but to many parameters. Got "
                . count($args)
                . ", expected "
                . $refl->getNumberOfParameters() . "."
            );
        }

        try {
            $res = $obj->$action(...$args);
        } catch (\ArgumentCountError $e) {
            throw new NotFoundException($e->getMessage());
        } catch (\TypeError $e) {
            throw new NotFoundException($e->getMessage());
        }

        return $res;
    }



    /**
     * Handle as callable support callables where the method is not static.
     *
     * @param string|array                 $action    base for the callable
     * @param array                        $arguments optional arguments
     * @param ContainerInjectableInterface $di        container with services
     *
     * @return mixed as the result from the route handler.
     */
    protected function handleAsCallable(
        $action,
        array $arguments
    ) {
        if (is_array($action)
            && isset($action[0])
            && isset($action[1])
            && is_string($action[0])
            && is_string($action[1])
            && class_exists($action[0])
        ) {
            // ["SomeClass", "someMethod"] but not static
            $refl = new \ReflectionMethod($action[0], $action[1]);
            if ($refl->isPublic() && !$refl->isStatic()) {
                $obj = new $action[0]();
                return $obj->{$action[1]}(...$arguments);
            }
        }

        // Add $di to param list, if defined by the callback
        $refl = is_array($action)
            ? new \ReflectionMethod($action[0], $action[1])
            : new \ReflectionFunction($action);
        $params = $refl->getParameters();
        if (isset($params[0]) && $params[0]->getName() === "di") {
            array_unshift($arguments, $this->di);
        }

        return call_user_func($action, ...$arguments);
    }



    /**
     * Load callable as a service from the $di container.
     *
     * @param string|array                 $action    base for the callable
     * @param array                        $arguments optional arguments
     * @param ContainerInjectableInterface $di        container with services
     *
     * @return mixed as the result from the route handler.
     */
    protected function handleUsingDi(
        $action,
        array $arguments,
        ContainerInterface $di
    ) {
        if (!$di->has($action[0])) {
            throw new ConfigurationException("Routehandler '{$action[0]}' not loaded in di.");
        }
    
        $service = $di->get($action[0]);
        if (!is_callable([$service, $action[1]])) {
            throw new ConfigurationException(
                "Routehandler '{$action[0]}' does not have a callable method '{$action[1]}'."
            );
        }
    
        return call_user_func(
            [$service, $action[1]],
            ...$arguments
        );
    }
}
