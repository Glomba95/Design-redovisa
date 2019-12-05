<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Testing compliance with PHP-FIG PSR-11.
 */
class Psr11ComplianceTest extends TestCase
{
    /**
     * Check that the interface is implemented.
     */
    public function testCheckInterfaceIsImplemented()
    {
        $di = new \Anax\DI\DI();
        $this->assertTrue($di instanceof ContainerInterface);

        $di = new \Anax\DI\DIFactoryConfig();
        $this->assertTrue($di instanceof ContainerInterface);

        $di = new \Anax\DI\DIMagic();
        $this->assertTrue($di instanceof ContainerInterface);
    }



    /**
     * Check if service exists in the container.
     */
    public function testContainerHasServiceOrNot()
    {
        $di = new \Anax\DI\DI();
        $di->set("service", "\stdClass");

        $res = $di->has("service");
        $this->assertTrue($res);

        $res = $di->has("nosuchservice");
        $this->assertFalse($res);
    }



    /**
     * Service is not loaded, exception implements interface.
     */
    public function testServiceIsNotLoaded()
    {
        $di = new \Anax\DI\DI();

        $wasThrown = false;
        try {
            $di->get("nosuchservice");
        } catch (\Exception $e) {
            $wasThrown = true;
            $this->assertTrue($e instanceof NotFoundExceptionInterface);
        }

        $this->assertTrue($wasThrown);
    }



    /**
     * Service fail to load, exception implements interface.
     */
    public function testServiceFailToLoad()
    {
        $di = new \Anax\DI\DI();
        $di->set("service", function () {
            throw new \Exception();
        });

        $wasThrown = false;
        try {
            $di->get("service");
        } catch (\Exception $e) {
            $wasThrown = true;
            $this->assertTrue($e instanceof ContainerExceptionInterface);
        }

        $this->assertTrue($wasThrown);
    }
}
