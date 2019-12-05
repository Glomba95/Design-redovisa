<?php

namespace Anax\TextFilter\Filter;

use \PHPUnit\Framework\TestCase;

/**
 * Test
 */
class VariableTest extends TestCase
{
    /**
     * Use a variable with a value.
     */
    public function testVariableWithValue()
    {
        $filter = new Variable();

        $text = <<<EOD
---
var: VALUE
---
-%var%-
This is text %var% and more text.
This is more text with %var%
EOD;

        $res = $filter->parse($text, ["var" => "VALUE"]);
        
        $this->assertContains(
            "-VALUE-",
            $res["text"]
        );
        
        $this->assertContains(
            "This is text VALUE and more text.",
            $res["text"]
        );
        
        $this->assertContains(
            "This is more text with VALUE",
            $res["text"]
        );
    }



    /**
     * Use a variable but no value.
     */
    public function testVariableNoValue()
    {
        $filter = new Variable();

        $text = <<<EOD
-%var%-
This is text %var% and more text.
This is more text with %var%
EOD;

        $res = $filter->parse($text, []);
        ;
        
        $this->assertContains(
            "-%var%-",
            $res["text"]
        );
        
        $this->assertContains(
            "This is text %var% and more text.",
            $res["text"]
        );
        
        $this->assertContains(
            "This is more text with %var%",
            $res["text"]
        );
    }
}
