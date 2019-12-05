<?php

namespace Anax\Commons;

use Psr\Container\ContainerInterface;

/**
 * Trait to implement the ContainerInjectableInterface that allows a class
 * to be injected with $di.
 */
trait ContainerInjectableTrait
{
    /**
     * @var ContainerInterface $di the dependency/service container.
     */
    protected $di;



    /**
     * Set the dependency/service container to use.
     *
     * @param ContainerInterface $di a dependency/service container
     *
     * @return self
     */
    public function setDI(ContainerInterface $di)
    {
        $this->di = $di;
        return $this;
    }
}
