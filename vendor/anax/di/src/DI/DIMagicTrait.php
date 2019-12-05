<?php
namespace Anax\DI;

use Anax\DI\Exception\Exception;

/**
 * Trait to use to make a DI container use magic for
 * getting services. A class using a DI container can
 * access $this->di->session; as an alternative to
 * $this->di->get("session");
 */
trait DIMagicTrait
{
    /**
     * Magic method to get a service.
     *
     * @param string $service name of the service.
     *
     * @return mixed as the service requested.
     */
    public function __get($service)
    {
        return $this->get($service);
    }



    /**
     * Magic method to get and call a service.
     *
     * @param string $service name of the service.
     * @param array  $arguments currently NOT USED.
     *
     * @return mixed as the service requested.
     */
    public function __call($service, $arguments = [])
    {
        return $this->get($service);
    }
}
