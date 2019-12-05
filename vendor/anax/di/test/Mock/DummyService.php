<?php

namespace Anax\DI;

/**
 * A dummy class to act as a service.
 */
class DummyService
{
    /**
     * @var string $property a dummy property.
     */
    public $property = "property";



    /**
     * A dummy method returning a string "method§".
     */
    public function method()
    {
        return "method";
    }
}
