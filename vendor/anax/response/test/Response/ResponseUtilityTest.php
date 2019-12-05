<?php

namespace Anax\Response;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test response utility class.
 */
class ResponseUtilityTest extends TestCase
{
    /**
     * A $di container.
     */
    private static $di;



    /**
     * Setup a fixture for all tests.
     */
    public static function setUpBeforeClass()
    {
        self::$di = new DIFactoryConfig();
        self::$di->loadServices([
           "services" => [
               "request" => [
                   "shared" => true,
                   "callback" => function () {
                       $obj = new MockRequest();
                       return $obj;
                   }
               ],
               "url" => [
                   "shared" => true,
                   "callback" => function () {
                       $obj = new MockUrl();
                       return $obj;
                   }
               ],
           ],
        ]);
    }



    /**
     * Test redirect method.
     */
    public function testRedirect()
    {
        $resp = new ResponseUtility();
        $resp->setDI(self::$di);

        $resp->redirect("/");
        $this->assertTrue(true);
    }



    /**
     * Test redirect method.
     */
    public function testRedirectSelf()
    {
        $resp = new ResponseUtility();
        $resp->setDI(self::$di);

        $resp->redirectSelf();
        $this->assertTrue(true);
    }
}
