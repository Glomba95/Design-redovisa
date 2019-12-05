<?php

namespace Anax\Response;

use \PHPUnit\Framework\TestCase;

/**
 * Test response module.
 */
class ResponseTest extends TestCase
{
    /**
     * Provider status codes.
     */
    public function statusCodesProvider()
    {
        return [
            [200],
            [400],
            [403],
            [404],
            [405],
            [500],
            [501],
        ];
    }



    /**
     * Try setting various status codes.
     *
     * @dataProvider statusCodesProvider
     */
    public function testStatusCodes($code)
    {
        $resp = new Response();
        $resp->setStatusCode($code);
        $result = $resp->getStatusCode();
        $this->assertEquals($code, $result);
    }



    /**
     * Set status code using null.
     */
    public function testStatusCodeNull()
    {
        $resp = new Response();

        $res = $resp->setStatusCode();
        $this->assertEquals($resp, $res);
    }



    /**
     * Add headers and try send them.
     */
    public function testAddSendHeaders()
    {
        $resp = new Response();

        $res = $resp->addHeader("HEADER");
        $this->assertEquals($resp, $res);

        $res = $resp->send();
        $this->assertEquals($resp, $res);

        $res = $resp->sendHeaders();
        $this->assertEquals($resp, $res);
    }



    /**
     * Add headers and get them again.
     */
    public function testAddGetHeaders()
    {
        $resp = new Response();

        $res = $resp->addHeader("HEADER");
        $this->assertEquals($resp, $res);

        $res = $resp->getHeaders();
        $this->assertIsArray($res);
        $this->assertContains("HEADER", $res);
    }



    /**
     * Add string to body.
     */
    public function testSetBodyAsString()
    {
        $resp = new Response();

        $exp = "body";
        $resp->setBody($exp);
        $res = $resp->getBody();
        $this->assertEquals($exp, $res);
    }



    /**
     * Set body as a callable.
     */
    public function testSetBodyAsCallable()
    {
        $resp = new Response();

        $resp->setBody(function () {
            return "Callable";
        });
        $res = $resp->getBody();
        $this->assertEquals("Callable", $res);
    }



    /**
     * Add json array to body.
     */
    public function testSetBodyAsArray()
    {
        $resp = new Response();

        $resp->setBody(["message" => "Hi"]);
        $res = $resp->getBody();
        $this->assertContains("message", $res);
        $this->assertContains("Hi", $res);

        $json = json_decode($res, true);
        $this->assertEquals($json, ["message" => "Hi"]);
    }



    /**
     * Send response as a string.
     */
    public function testSendAsString()
    {
        $resp = new Response();

        ob_start();
        $res = $resp->send("Hi");
        $body = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($resp, $res);

        $res = $body;
        $this->assertContains("Hi", $res);
    }



    /**
     * Send response as a response object.
     */
    public function testSendAsResponseObject()
    {
        $resp = new Response();
        $resp1 = new Response();
        $resp1->setBody("Hi");

        ob_start();
        $res = $resp->send($resp1);
        $body = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($resp1, $res);

        $res = $body;
        $this->assertContains("Hi", $res);
    }



    /**
     * Pass status code to send() within array.
     */
    public function testSendWithStatusCode()
    {
        $resp = new Response();

        ob_start();
        $res = $resp->send(["Hi", 200]);
        $body = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($resp, $res);

        $res = $body;
        $this->assertContains("Hi", $res);
    }



    /**
     * Add json array to body using send().
     */
    public function testSendAsArray()
    {
        $resp = new Response();

        ob_start();
        $res = $resp->send([["message" => "Hi"]]);
        $body = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($resp, $res);

        $res = $body;
        $this->assertContains("message", $res);
        $this->assertContains("Hi", $res);

        $json = json_decode($res, true);
        $this->assertEquals($json, ["message" => "Hi"]);
    }



    /**
     * Send json data as response.
     */
    public function testSendAsJsonData()
    {
        $resp = new Response();

        ob_start();
        $res = $resp->sendJson(["message" => "Hi"]);
        $body = ob_get_contents();
        ob_end_clean();
        $this->assertEquals($resp, $res);

        $res = $body;
        $this->assertContains("message", $res);
        $this->assertContains("Hi", $res);

        $json = json_decode($res, true);
        $this->assertEquals($json, ["message" => "Hi"]);
    }



    // /**
    //  * Redirect to another page.
    //  */
    // public function testRedirect()
    // {
    //     $resp = new Response();
    //
    //     $resp->redirect("/");
    // }
}
