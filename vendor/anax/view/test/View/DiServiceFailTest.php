<?php

namespace Anax\View;

use Anax\Configure\Configuration;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try that a DI service can be created from the di config file.
 */
class DiServiceFailTest extends TestCase
{
    /**
     * Create the service with config file missing paths.
     *
     * @expectedException \Anax\DI\Exception\Exception
     */
    public function testNoPaths()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("view.php", ANAX_INSTALL_PATH . "/test/config/view_no_paths.php");
        $di->set("configuration", $cfg);

        $di->get("view");
    }
}
