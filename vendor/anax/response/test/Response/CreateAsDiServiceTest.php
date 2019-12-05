<?php

namespace Anax\Response;

use Anax\DI\DIFactoryConfig;
use Anax\Configure\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * Try that a DI service can be created from the di config file.
 */
class CreateAsDiServiceTest extends TestCase
{
    /**
     * Create the service.
     */
    public function testCreateDiService()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $di->set("configuration", $cfg);

        $response = $di->get("response");
        $this->assertInstanceOf(Response::class, $response);
    }
}
