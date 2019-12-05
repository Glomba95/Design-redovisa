<?php

namespace Anax\Route;

use Anax\Configure\Configuration;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * A DI service can not be created when configuration has flaws.
 */
class DiServiceFailTest extends TestCase
{
    /**
     * Router configuration file has error.
     *
     * @expectedException \Exception
     */
    public function testConfigurationFileHasError()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("router", ANAX_INSTALL_PATH . "/test/config/router_error.php");
        $di->set("configuration", $cfg);

        $di->get("router");
    }
}
