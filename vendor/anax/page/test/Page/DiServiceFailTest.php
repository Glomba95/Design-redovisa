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
     * Missing a layout.
     *
     * @expectedException \Anax\DI\Exception\Exception
     * @expectedExceptionMessage Missing configuration for layout
     */
    public function testNoPaths()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("page.php", ANAX_INSTALL_PATH . "/test/config/page_no_layout.php");
        $di->set("configuration", $cfg);

        $view = new ViewCollection();
        $view->setPaths([ANAX_INSTALL_PATH . "/vendor/anax/view/view"]);
        $di->set("view", $view);

        $di->get("page");
    }
}
