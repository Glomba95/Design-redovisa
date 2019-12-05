<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;

/**
 * A DI variant with magic methods..
 */
class DIMagicTest extends TestCase
{
    /**
     * Load services from an array.
     */
    public function testLoadServicesFromArray()
    {
        $di = new DIMagic();
        $self = $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        $this->assertInstanceOf(DiFactoryConfig::class, $self);

        $services = $di->getServices();
        $defaultServices = [
            "response",
            "request",
            "session",
            "url",
        ];

        foreach ($defaultServices as $service) {
            $this->assertContains($service, $services);
        }

        $service = $di->response();
        $this->assertInstanceOf(\stdClass::class, $service);

        $service = $di->request;
        $this->assertInstanceOf(\stdClass::class, $service);
    }
}
