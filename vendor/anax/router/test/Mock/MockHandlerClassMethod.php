<?php

namespace Anax\Route;

/**
 * A mock handler as a class and a method.
 */
class MockHandlerClassMethod
{
    public function method()
    {
        return "handler";
    }

    public static function static()
    {
        return "handler";
    }
}
