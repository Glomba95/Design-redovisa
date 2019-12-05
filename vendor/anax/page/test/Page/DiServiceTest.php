<?php

namespace Anax\Page;

use Anax\Configure\Configuration;
use Anax\DI\DIFactoryConfig;
use Anax\View\ViewCollection;
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
        $di->set("configuration", $cfg);

        $view = new ViewCollection();
        $view->setPaths([ANAX_INSTALL_PATH . "/vendor/anax/view/view"]);
        $di->set("view", $view);

        $page = $di->get("page");
        $this->assertInstanceOf(Page::class, $page);
    }
}
