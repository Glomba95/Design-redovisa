<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Testing compliance with PHP-FIG PSR-11.
 */
class Psr11ComplianceFailTest extends TestCase
{
    /**
     * Service is not loaded.
     *
     * @expectedException Anax\DI\Exception\NotFoundException
     */
    public function testServiceIsNotLoaded()
    {
        $di = new \Anax\DI\DI();
        $di->get("nosuchservice");
    }



    /**
     * Service fail to load.
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testServiceFailToLoad()
    {
        $di = new \Anax\DI\DI();
        $di->set("service", function () {
            throw new \Exception();
        });
        $di->get("service");
    }
}
