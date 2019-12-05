<?php

namespace Anax\Route;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

/**
 * A mock handler as a controller app style.
 */
class MockHandlerAppController implements AppInjectableInterface
{
    use AppInjectableTrait;

    public function appAction()
    {
        return $this->app;
    }

    public function indexAction()
    {
        return "indexAction";
    }
}
