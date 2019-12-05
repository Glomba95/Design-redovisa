<?php

namespace Anax\Commons;

use Psr\Container\ContainerInterface;

/**
 * Interface to implement for DI aware services to show off they are ready
 * to be injected with $di.
 */
interface ContainerInjectableInterface
{
    /**
     * Set the service container to use
     *
     * @param ContainerInterface $di a dependency/service container
     *
     * @return $this
     */
    public function setDI(ContainerInterface $di);
}
