<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;

/**
 * Negative tests for configurable DI container..
 */
class DIFactoryConfigFailTest extends TestCase
{
    /**
     * Load services with invalid reference, empty array.
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testLoadServicesWithInvalidReferenceEmptyArray()
    {
        $di = new DIFactoryConfig();
        $di->loadServices([]);
    }



    /**
     * Load services with invalid reference, invalid path.
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testLoadServicesWithInvalidReferenceInvalidPath()
    {
        $di = new DIFactoryConfig();
        $di->loadServices("invalid/path");
    }



    /**
     * Load services with invalid reference, invalid path.
     *
     * @expectedException Anax\DI\Exception\Exception
     */
    public function testLoadServicesMissingCallback()
    {
        $di = new DIFactoryConfig();
        $di->loadServices([
            "services" => [
                "request" => [
                    "shared" => false,
                    "callback" => null
                ],
            ],
        ]);
    }
}
