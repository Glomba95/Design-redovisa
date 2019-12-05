<?php

namespace Anax\Commons;

use Psr\Container\ContainerInterface;

/**
 * Trait to implement the AppInjectableInterface that allows a class
 * to be injected with $app.
 */
trait AppInjectableTrait
{
    /**
     * @var ContainerInterface $app the dependency/service container.
     */
    protected $app;



    /**
     * Set the dependency/service container to use.
     *
     * @param ContainerInterface $app a dependency/service container
     *
     * @return self
     */
    public function setApp(ContainerInterface $app)
    {
        $this->app = $app;
        return $this;
    }
}
