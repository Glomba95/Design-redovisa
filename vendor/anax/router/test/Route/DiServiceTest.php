<?php

namespace Anax\Route;

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
        $di->set("configuration", $cfg);

        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);
    }



    /**
     * Create the service from default config file, check development mode
     * is used.
     *
     * @expectedException \Exception
     */
    public function testCreateDiServiceDevelopmentMode()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $di->set("configuration", $cfg);

        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->handle("test/exception");
    }



    /**
     * Default config file but ANAX_PRODUCTION is set.
     */
    public function testCreateDiServiceAnaxProduction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $di->set("configuration", $cfg);

        define("ANAX_PRODUCTION", true);
        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $res = $router->handle("test/exception");
        $this->assertEquals(2, count($res));
        $this->assertEquals("Anax 500: Internal Server Error", $res[0]);
        $this->assertEquals(500, $res[1]);
    }



    /**
     * Config file with $mode set to development.
     *
     * @expectedException \Exception
     */
    public function testModeIsDevelopment()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("router", ANAX_INSTALL_PATH . "/test/config/router_mode_development.php");
        $di->set("configuration", $cfg);

        $configuration = $di->get("configuration");
        $this->assertInstanceOf(Configuration::class, $configuration);

        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/800_test.php");
        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/900_internal.php");

        $router->handle("test/exception");
    }



    /**
     * Config file with $mode set to production.
     */
    public function testModeIsProduction()
    {
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
    
        $cfg = new Configuration();
        $cfg->setBaseDirectories([ANAX_INSTALL_PATH . "/config"]);
        $cfg->setMapping("router", ANAX_INSTALL_PATH . "/test/config/router_mode_production.php");
        $di->set("configuration", $cfg);
    
        $configuration = $di->get("configuration");
        $this->assertInstanceOf(Configuration::class, $configuration);

        $router = $di->get("router");
        $this->assertInstanceOf(Router::class, $router);

        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/800_test.php");
        $router->addRoutes(require ANAX_INSTALL_PATH . "/config/router/900_internal.php");

        $res = $router->handle("test/exception");
        $this->assertEquals(2, count($res));
        $this->assertEquals("Anax 500: Internal Server Error", $res[0]);
        $this->assertEquals(500, $res[1]);
    }
}
