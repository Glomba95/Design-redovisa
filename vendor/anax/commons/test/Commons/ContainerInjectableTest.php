<?php

namespace Anax\Commons;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try that a DI service can be created from the di config file.
 */
class ContainerInjectableTest extends TestCase
{
    /**
     * Inject $di into a class.
     */
    public function testInjectDi()
    {
        $mock = new MockContainerInjectable();
        $di = new DIFactoryConfig();

        $mock->setDI($di);
        $res = $mock->getDI();
        $this->assertInstanceOf(DIFactoryConfig::class, $res);
    }
}
