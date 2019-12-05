<?php

namespace Anax\Commons;

use Psr\Container\ContainerInterface;

/**
 * Interface to implement for App aware services to show off they are ready
 * to be injected with $app.
 */
interface AppInjectableInterface
{
    /**
     * Set the service container to use
     *
     * @param ContainerInterface $app a dependency/service container
     *
     * @return $this
     */
    public function setApp(ContainerInterface $app);
}
