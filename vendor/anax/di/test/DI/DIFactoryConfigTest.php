<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;

/**
 * Testing the Dependency Injector service container.
 */
class DIFactoryConfigTest extends TestCase
{
    /**
     * Load services from an array.
     */
    public function testLoadServicesFromArray()
    {
        $di = new DIFactoryConfig();

        $self = $di->loadServices([
            "services" => [
                "request" => [
                    "active" => true,
                    "shared" => false,
                    "callback" => function () {
                        $object = new \stdClass();
                        return $object;
                    }
                ],
            ],
        ]);

        $this->assertInstanceOf(DiFactoryConfig::class, $self);

        $services = $di->getServices();
        $defaultServices = [
            "request",
        ];

        foreach ($defaultServices as $service) {
            $this->assertContains($service, $services);
        }
    }



    /**
     * Load services from a configuration file.
     */
    public function testLoadServicesFromFile()
    {
        $di = new DIFactoryConfig();
        $self = $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di.php");

        $this->assertInstanceOf(DiFactoryConfig::class, $self);

        $services = $di->getServices();
        $defaultServices = [
            "request",
            "response",
        ];

        foreach ($defaultServices as $service) {
            $this->assertContains($service, $services);
        }
    }



    /**
     * Load services from a configuration file/dir.
     */
    public function testLoadServicesFromFileDir()
    {
        $di = new DIFactoryConfig();
        $self = $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $this->assertInstanceOf(DiFactoryConfig::class, $self);

        $services = $di->getServices();
        $defaultServices = [
            "request",
            "response",
            "session",
            "url"
        ];

        foreach ($defaultServices as $service) {
            $this->assertContains($service, $services);
        }
    }
}
