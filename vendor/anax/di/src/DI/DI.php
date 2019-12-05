<?php

namespace Anax\DI;

use Anax\DI\Exception\Exception;
use Anax\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * A dependency/service container implementing the interface FIG PSR-11.
 * It can contain any of the fraework services, using lazy loading.
 */
class DI implements ContainerInterface
{
    /**
     * @var array $loaded  Store all lazy loaded services, ready to be
     *                     instantiated.
     * @var array $active  A service is instantiated into this array,
     *                     once its accessed.
     */
    protected $loaded = [];
    protected $active = [];



    /**
     * Finds an entry in the container by its identifier and returns it.
     * If the service is active/singelton then that instance is returned,
     * else the service is loaded and a new instance is returned.
     *
     * @param string $service     Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws Exception          Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($service)
    {
        if (!$this->has($service)) {
            throw new NotFoundException("The service '$service' is not loaded in the DI-container.");
        }

        if (isset($this->active[$service])
            && $this->loaded[$service]['singleton']) {
            return $this->active[$service];
        }

        return $this->load($service);
    }



    /**
     * Load a prepared service object and create an instance of it.
     *
     * @param string $service as a service label, naming this service.
     *
     * @throws Exception when service could not be loaded.
     *
     * @return object as instance of the service object.
     */
    protected function load($service)
    {
        $sol = $this->loaded[$service]['loader'] ?? null;

        if (is_callable($sol)) {
            // Load by calling a function
            try {
                $this->active[$service] = $sol();
            } catch (\Exception $e) {
                throw new Exception(
                    "Could not load service '$service'."
                    . "Failed in the callback that instantiates the service. "
                    . $e->getMessage()
                );
            }
        } elseif (is_object($sol)) {
            // Load by pre-instantiated object
            $this->active[$service] = $sol;
        } elseif (is_string($sol)) {
            // Load by creating a new object from class-string
            if (!class_exists($sol)) {
                throw new Exception(
                    "Could not load service '$service'."
                    . "Class '$sol' does not exists."
                );
            }
            $this->active[$service] = new $sol();
        } else {
            throw new Exception("The service '$service' has no init mechanism.");
        }

        $this->$service = $this->active[$service];
        return $this->active[$service];
    }



    /**
     * Returns true if the container can return an entry for the given
     * identifier, otherwise false
     *
     * @param string $service Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($service)
    {
        return isset($this->loaded[$service]);
    }



    /**
     * Set a service and connect it to a task which creates the object
     * (lazy loading).
     *
     * @param string  $service   as a service label, naming this service.
     * @param mixed   $loader    contains a pre-defined object, a string with
     *                           class name or an callable which returns an
     *                           instance of the service object. Its the way
     *                           to actually load, insantiate, the service
     *                           object.
     *
     * @return void
     */
    public function set($service, $loader)
    {
        $this->loaded[$service]['loader'] = $loader;
        $this->loaded[$service]['singleton'] = false;
    }



    /**
     * Set a singleton service and connect it to a task which creates the
     * object (lazy loading).
     *
     * @param string $service as a service label, naming this service.
     * @param mixed   $loader    contains a pre-defined object, a string with
     *                           class name or an callable which returns an
     *                           instance of the service object. Its the way
     *                           to actually load, insantiate, the service
     *                           object.
     *
     * @return void
     */
    public function setShared($service, $loader)
    {
        $this->loaded[$service]['loader'] = $loader;
        $this->loaded[$service]['singleton'] = true;
    }



    /**
     * Return an array with all loaded services names.
     *
     * @return array
     */
    public function getServices()
    {
        return array_keys($this->loaded);
    }



    /**
     * Return an array with all active services names.
     *
     * @return array
     */
    public function getActiveServices()
    {
        return array_keys($this->active);
    }
}
