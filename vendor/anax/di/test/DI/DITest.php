<?php

namespace Anax\DI;

use PHPUnit\Framework\TestCase;

/**
 * Testing the Dependency Injector service container.
 */
class DITest extends TestCase
{
    /**
     * Create a service instantiating using a callback.
     */
    public function testSetUsingCallback()
    {
        $di = new DI();
        $service = 'someService';

        $di->set($service, function () {
            return new \stdClass();
        });

        $someService = $di->get($service);
        $this->assertInstanceOf('\stdClass', $someService);
    }



    /**
     * Get a loaded shared service.
     */
    public function testGetLoadedSharedService()
    {
        $di = new DI();
        $service = 'someService';

        $di->setShared($service, function () {
            return new \stdClass();
        });

        $someService = $di->get($service);
        $this->assertInstanceOf('\stdClass', $someService);

        $someService = $di->get($service);
        $this->assertInstanceOf('\stdClass', $someService);
    }



    /**
     * Create a service instantiating using a string.
     */
    public function testSetUsingString()
    {
        $di = new DI();
        $service = 'someService';

        $di->set($service, "\stdClass");

        $someService = $di->get($service);
        $this->assertInstanceOf('\stdClass', $someService);
    }



    /**
     * Create a service instantiating using a prepared object.
     */
    public function testSetUsingObject()
    {
        $di = new DI();
        $service = 'someService';
        $object = new \stdClass();

        $di->set($service, $object);

        $someService = $di->get($service);
        $this->assertInstanceOf('\stdClass', $someService);
    }



    /**
     * Use set to overwrite a service that was previously created.
     */
    public function testOverwritePreviousDefinedService()
    {
        $di = new DI();
        $service = 'session';
    
        $di->set($service, function () {
            $session = new \stdClass();
            return $session;
        });
    
        $session = $di->get($service);
        $this->assertInstanceOf('\stdClass', $session);
    }
    
    
    
    /**
     * Add and access a dummy service.
     */
    public function testDummyService()
    {
        $di = new DI();
    
        $di->set("dummy", function () {
            $obj = new DummyService();
            return $obj;
        });
    
        $obj = $di->get("dummy");
        $this->assertInstanceOf('\Anax\DI\DummyService', $obj);
    
        $res = $di->get("dummy")->property;
        $this->assertEquals("property", $res);
    
        $res = $di->get("dummy")->method();
        $this->assertEquals("method", $res);
    }



    /**
     * Get active services.
     */
    public function testGetActiveServices()
    {
        $di = new DI();
    
        $di->set("service1", function () {
            return new \stdClass();
        });
    
        $di->set("service2", function () {
            return new \stdClass();
        });
    
        
        $obj = $di->get("service1");
        $this->assertInstanceOf('\stdClass', $obj);
    
        $res = $di->getActiveServices();
        $this->assertContains("service1", $res);
        $this->assertNotContains("service2", $res);
    }
}
