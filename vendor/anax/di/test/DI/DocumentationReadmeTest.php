<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Testing the README.md constructs.
 */
class DocumentationReadmeTest extends TestCase
{
    /**
     * Create and add a service and use it.
     */
    public function testCreateAndAddServiceAndUseIt()
    {
        // Create it
        $di = new \Anax\DI\DI();

        // Check its a PSR-11 interface
        //assert($di instanceof \Psr\Container\ContainerInterface);
        $this->assertTrue($di instanceof ContainerInterface);

        // Add a service
        //$di->set("response", "\Anax\Response\Response");
        $di->set("response", "\Anax\DI\DummyService");

        // Add a shared service
        //$di->setShared("view", "\Anax\View\ViewContainer");
        $di->setShared("view", "\Anax\DI\DummyService");

        // Check if service is loaded
        if ($di->has("view")) {
            ; // the service is loaded
        }
        $this->assertTrue($di->has("view"));

        // Get and use a service
        $response = $di->get("response");
        //$response->addBody($body)->send();
        $response->method();

        // Same, without storing in a variable
        //$di->get("response")->addBody($body)->send();
        $di->get("response")->method();
    }



    /**
     * Add and use a shared service.
     */
    public function testAddAndUseSharedService()
    {
        $di = new \Anax\DI\DI();

        // Add a shared service
        //$di->setShared("view", "\Anax\View\ViewContainer");
        $di->setShared("view", "\Anax\DI\DummyService");

        // Get two instances of the shared service
        $view1 = $di->get("view");
        $view2 = $di->get("view");
        $res = assert($view1 === $view2);
        $this->assertTrue($res);
    }



    /**
     * Add and use a service.
     */
    public function testAddAndUseService()
    {
        $di = new \Anax\DI\DI();

        // Add a shared service
        //$di->set("response", "\Anax\Response\Response");
        $di->set("response", "\Anax\DI\DummyService");

        // Get two instances of the service
        $response1 = $di->get("response");
        $response2 = $di->get("response");
        $res = assert($response1 !== $response2);
        $this->assertTrue($res);
    }



    /**
     * Check lazy loading.
     */
    public function testLazyLoading()
    {
        $di = new \Anax\DI\DI();

        // Add services
        // $di->set("response", "\Anax\Response\Response");
        // $di->setShared("view", "\Anax\View\ViewContainer");
        $di->set("response", "\Anax\DI\DummyService");
        $di->setShared("view", "\Anax\DI\DummyService");

        // Get one service
        $response = $di->get("response");
        $this->assertTrue($response instanceof DummyService);

        // Check what services are loaded
        $res = implode(",", $di->getServices()); // response,view
        $this->assertEquals("response,view", $res);

        // Check what services are active
        $res = implode(",", $di->getActiveServices()); // response
        $this->assertEquals("response", $res);
    }
}
