<?php

namespace Anax\DI;

use Anax\DI\Exception\Exception;
use Anax\DI\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Testing the Dependency Injector service container.
 */
class DIFailTest extends TestCase
{
    /**
     * A user can not throw a custom exception in the callback initiating
     * the service. It will result in a DIException
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testLoadFailesInServiceCreationWithCustomException()
    {
        $di = new DI();
        $service = 'failsWithException';

        $di->set($service, function () {
            throw new \Exception("Failed creating service.");
        });

        $di->get($service);
    }



    /**
     * Using unknown classname as a string to load the service.
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testLoadFailesInServiceCreationWithUnknownClassname()
    {
        $di = new DI();
        $service = 'failsWithException';

        $di->set($service, "NO_EXISTING_CLASS");

        $di->get($service);
    }



    /**
     * Service has no callable init mecanism.
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testNoInitCallable()
    {
        $di = new DI();
        $service = 'failsWithException';

        $di->set($service, null);
        $di->get($service);
    }



    /**
     * Loading service that does not exists.
     *
     * @expectedException Anax\DI\Exception\NotFoundException
     */
    public function testServiceDoesNotExists()
    {
        $di = new DI();
        $this->assertFalse($di->has("nono"));
        $di->get("nono");
    }
}
