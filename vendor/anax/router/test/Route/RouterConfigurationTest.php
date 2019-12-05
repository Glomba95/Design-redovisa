<?php

namespace Anax\Route;

use PHPUnit\Framework\TestCase;
use Anax\Route\Exception\ConfigurationException;

/**
 * Test configuration of the router.
 */
class RouterConfigurationTest extends TestCase
{
    /**
     * Add mount to mount
     */
    public function testConfigAddMountToMount()
    {
        $router = new Router();
        $router->addRoutes([
            "mount" => "somewhere",
            "routes" => [
                [
                    "mount" => "mount",
                    "path" => "path",
                    "handler" => function () {
                        return "somewhere/mount/path";
                    }
                ]
            ]
        ]);
        $res = $router->handle("somewhere/mount/path");
        $this->assertEquals("somewhere/mount/path", $res);
    }



    /**
     * Mountpoint through mount within route.
     */
    public function testConfigAddMountWithRoute()
    {
        $router = new Router();
        $router->addRoutes([
            "mount" => null,
            "routes" => [
                [
                    "mount" => "mount",
                    "path" => "path",
                    "handler" => function () {
                        return "mount/path";
                    }
                ]
            ]
        ]);
        $res = $router->handle("mount/path");
        $this->assertEquals("mount/path", $res);
    }
}
