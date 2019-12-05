<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * A testclass
 *
 */
class TextFilterFilterMoreAndStopTest extends TestCase
{
     /**
      * Test <!--more-->
      */
    public function testMore()
    {
        $filter = new TextFilter();

        $text = "";
        $exp  = "";
        $res = $filter->parse($text, []);
        $filter->addExcerpt($res);
        $this->assertEquals($exp, $res->excerpt, "More did not match");

        $text = "A<!--more-->B";
        $exp  = "A";
        $res = $filter->parse($text, []);
        $filter->addExcerpt($res);
        $this->assertEquals($exp, $res->excerpt, "More did not match");

        $text = "A<!--stop-->B<!--more-->C";
        $exp  = "A";
        $res = $filter->parse($text, []);
        $filter->addExcerpt($res);
        $this->assertEquals($exp, $res->excerpt, "More did not match");
    }



    /**
    * Test <!--stop-->
     */
    public function testStop()
    {
        $filter = new TextFilter();

        $text = "";
        $exp  = "";
        $res = $filter->parse($text, []);
        $filter->addExcerpt($res);
        $this->assertEquals($exp, $res->excerpt, "Stop did not match");

        $text = "A<!--stop-->B";
        $exp  = "A";
        $res = $filter->parse($text, []);
        $filter->addExcerpt($res);
        $this->assertEquals($exp, $res->excerpt, "Stop did not match");
    }
}
