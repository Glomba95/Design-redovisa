<?php

namespace Anax\Commons;

/**
 * A mock class implementing interface and trait to be injectable.
 */
class MockContainerInjectable implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function getDI() : object
    {
        return $this->di;
    }
}
