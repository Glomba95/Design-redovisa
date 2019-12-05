<?php

namespace Anax;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the t() function.
 */
class FunctionTTest extends TestCase
{
    /**
     * Test it returns plain string.
     */
    public function testPlain()
    {
        $str = "This is a text.";
        $this->assertEquals($str, t($str));
    }



    /**
     * Use pass through arguments.
     */
    public function testArgumentPassThrough()
    {
        $value = "42";

        $str = "This is !value";
        $arg = ["!value" => $value];
        $res = t($str, $arg);
        $this->assertEquals("This is 42", $res);

        $str = "This is !value !value";
        $res = t($str, $arg);
        $this->assertEquals("This is 42 42", $res);
    }



    /**
     * Use htmlentities arguments.
     */
    public function testArgumentEntites()
    {
        $value = "å";
        $str = "This is @value";
        $arg = ["@value" => $value];
        $res = t($str, $arg);
        $this->assertEquals("This is &aring;", $res);

        $str = "This is @value @value";
        $res = t($str, $arg);
        $this->assertEquals("This is &aring; &aring;", $res);
    }
}
