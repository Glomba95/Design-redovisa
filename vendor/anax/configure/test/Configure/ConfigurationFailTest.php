<?php

namespace Anax\Configure;

use \PHPUnit\Framework\TestCase;

/**
 * A test class.
 */
class ConfigurationFailTest extends TestCase
{
    /**
     * Base directories for configuration.
     */
    protected $dirs = [
        __DIR__ . "/../config1",
        __DIR__ . "/../config2",
    ];



    /**
     * Throw exception when configuration files are missing.
     *
     * @expectedException Exception
     */
    public function testMissingConfigFile()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $cfg->load("MISSING");
    }



    /**
     * Throw exception when no base dirs are defined.
     *
     * @expectedException Exception
     */
    public function testNoBaseDirsAreDefined()
    {
        $cfg = new Configuration();
        $cfg->load("view");
    }



    /**
     * Throw exception when setting base dirs to an empty array.
     *
     * @expectedException Exception
     */
    public function testSetBaseDirsAsEmptyArray()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories([]);
    }



    /**
     * Throw exception when path to base dir is wrong.
     *
     * @expectedException Exception
     */
    public function testSetBaseDirsWithWrongPath()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories(["no path"]);
    }
}
