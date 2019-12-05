<?php

namespace Anax\DI;

use Anax\DI\Exception\Exception;
use Psr\Container\ContainerInterface;

/**
 * DI factory class creating a set of default services by loading
 * them from a configuration array, file and/or directory.
 *
 * The general configuration array for a service looks like this.
 * [
 *     "services" => [
 *         "request" => [
 *             "active" => false,
 *             "shared" => false,
 *             "callback" => function () {
 *                 $object = new \stdClass();
 *                 return $object;
 *             }
 *         ],
 *     ],
 * ]
 */
class DIFactoryConfig extends DI implements ContainerInterface
{
    /**
     * Create services by using $item as a reference to find a
     * configuration for the services. The $item can be an array,
     * a file.php, or an directory containing files named *.php.
     *
     * @param array|string $item referencing the source for configuration.
     *
     * @return $this
     */
    public function loadServices($item) : object
    {
        if (is_array($item)) {
            $this->createServicesFromArray($item, "array");
            return $this;
        }

        if (is_readable($item) && is_file($item)) {
            $services = require $item;
            $this->createServicesFromArray($services, $item);
            return $this;
        }

        $found = false;
        if (is_readable("$item.php") && is_file("$item.php")) {
            $services = require "$item.php";
            $this->createServicesFromArray($services, $item);
            $found = true;
        }
    
        if (is_readable($item) && is_dir($item)) {
            foreach (glob("$item/*.php") as $file) {
                $services = require "$file";
                $this->createServicesFromArray($services, $file);
                $found = true;
            }
        }

        if (!$found) {
            throw new Exception("Item to load configuration from was no file, dir nor an array.");
        }

        return $this;
    }



    /**
     * Create services from an array containing a list of services.
     *
     * @param array  $service details to use when creating the service.
     *
     * @throws Exception when configuration is corrupt.
     *
     * @return void
     */
    protected function createServicesFromArray(
        array $services,
        string $path
    ) : void {
        if (!isset($services["services"])) {
            throw new Exception("The configuration array is missing the key 'services' in file '$path'.");
        }

        foreach ($services["services"] as $name => $service) {
            try {
                $this->createService($name, $service);
            } catch (Exception $e) {
                throw new Exception($e->getMessage() . " In configuration file '$path'.");
            }
        }
    }



    /**
     * Create a service from a name and an array containing details on
     * how to create it.
     *
     * @param string $name    of service.
     * @param array  $service details to use when creating the service.
     *
     * @throws Exception when configuration is corrupt.
     *
     * @return void
     */
    protected function createService(string $name, array $service) : void
    {
        if (!isset($service["callback"])) {
            throw new Exception("The service '$name' is missing a callback.");
        }

        if (isset($service["shared"]) && $service["shared"]) {
            $this->setShared($name, $service["callback"]);
        } else {
            $this->set($name, $service["callback"]);
        }

        if (isset($service["active"]) && $service["active"]) {
            $this->get($name);
        }
    }
}
