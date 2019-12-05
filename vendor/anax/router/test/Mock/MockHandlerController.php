<?php

namespace Anax\Route;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A mock handler as a controller.
 */
class MockHandlerController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function diAction()
    {
        return $this->di;
    }

    public function initialize()
    {
        return "initialize";
    }

    public function indexAction()
    {
        return "indexAction";
    }

    public function indexActionGET()
    {
        return "indexActionGET";
    }

    public function indexActionPOST()
    {
        return "indexActionPOST";
    }

    public function createAction()
    {
        return "createAction";
    }

    public function listAction()
    {
        return "listAction";
    }

    public function viewAction(int $id)
    {
        return "viewAction id:$id";
    }

    public function searchAction(string $str)
    {
        return "searchAction str:$str";
    }

    public function testAction(int $id, string $str)
    {
        return "testAction id:$id str:$str";
    }

    public function variadicAction(...$collection)
    {
        return "variadicAction collection:" . count($collection);
    }

    private function privateAction()
    {
        return "privateAction";
    }
}
