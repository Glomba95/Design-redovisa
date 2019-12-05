<?php

namespace Anax\Configure;

use \PHPUnit\Framework\TestCase;

/**
 * A test class.
 */
class ConfigurationTest extends TestCase
{
    /**
     * Base directories for configuration.
     */
    protected $dirs = [
        __DIR__ . "/../config1",
        __DIR__ . "/../config2",
    ];



    /**
     * Load configuration from file alone.
     */
    public function testConfigFromSingleFile()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $config = $cfg->load("view");

        $this->assertInternalType("array", $config);
        $this->assertArrayHasKey("file", $config);
        $this->assertArrayHasKey("config", $config);
        $this->assertArrayNotHasKey("items", $config);
        $this->assertContains("a view", $config["config"]);
    }



    /**
     * Load configuration from directory alone.
     */
    public function testConfigFromDirectory()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $config = $cfg->load("response");

        $this->assertInternalType("array", $config);
        $this->assertArrayNotHasKey("file", $config);
        $this->assertArrayNotHasKey("config", $config);
        $this->assertArrayHasKey("items", $config);
        $this->assertContains("part1", $config["items"][0]["config"]);
        $this->assertContains("part2", $config["items"][1]["config"]);
    }



    /**
     * Load configuration from file and directory.
     */
    public function testConfigFromFileAndDirectory()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $config = $cfg->load("route");

        $this->assertInternalType("array", $config);
        $this->assertArrayHasKey("file", $config);
        $this->assertArrayHasKey("config", $config);
        $this->assertContains("a route", $config["config"]);
        $this->assertArrayHasKey("items", $config);
        $this->assertContains("a 404 route", $config["items"][0]["config"]);
        $this->assertContains("an internal route", $config["items"][1]["config"]);
    }



    /**
     * Use mapping to load specific file for a configuration item.
     */
    public function testUseMapping()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $cfg->setMapping("item", ANAX_INSTALL_PATH . "/test/config/test1.php");
        $config = $cfg->load("item");

        $this->assertInternalType("array", $config);
        $this->assertArrayHasKey("file", $config);
        $this->assertArrayHasKey("config", $config);
        $this->assertArrayHasKey("key1", $config["config"]);
        $this->assertContains("value1", $config["config"]);
    }
}
