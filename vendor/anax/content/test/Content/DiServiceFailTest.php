<?php

namespace Anax\Page;

use Anax\Configure\Configuration;
use Anax\DI\DIFactoryConfig;
use Anax\View\ViewCollection;
use PHPUnit\Framework\TestCase;

/**
 * Try that a DI service can be created from the di config file.
 */
class DiServiceFailTest extends TestCase
{
    /**
     * Check some error.
     *
     * @expected Exception \Anax\DI\Exception\Exception
     * @expectedExceptionMessage Missing configuration for layout
     */
    public function testNoPaths()
    {
        $this->assertTrue(true);
    }
}
