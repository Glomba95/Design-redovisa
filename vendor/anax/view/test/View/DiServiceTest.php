<?php

namespace Anax\View;

use Anax\Configure\Configuration;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Try that a DI service can be created from the di config file.
 */
class DiServiceTest extends TestCase
{
    /**
     * Create the service from default config file.
     */
    public function testCreateDiService()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("view.php", ANAX_INSTALL_PATH . "/test/config/view.php");
        $di->set("configuration", $cfg);

        $view = $di->get("view");
        $this->assertInstanceOf(ViewCollection::class, $view);
    }
}
