<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * Testing the filter variable through textfilter parse.
 */
class TextFilterFilterVariableTest extends TestCase
{
    /**
     * Configuration for the frontmatter filter.
     */
    private $options = [
        "include" => false,
    ];



    /**
     * Test having current filter.
     */
    public function testHavingCurrentFilter()
    {
        $filter = new TextFilter();

        $this->assertTrue($filter->hasFilter("variable"));
    }



    /**
     * Test single variable by using YAML.
     */
    public function testSingleVariableUsingYaml()
    {
        $filter = new TextFilter();

        $text = <<<EOD
---
text: Awesome
---
%text%
EOD;

        $filter->setFilterConfig("frontmatter", $this->options);
        $res = $filter->parse($text, ["frontmatter", "variable"]);
        
        $this->assertEquals([
            "text" => "Awesome"
        ], $res->frontmatter);
        
        $this->assertEquals(
            "Awesome",
            $res->text
        );
    }



    /**
     * Test single variable by using JSON.
     */
    public function testSingleVariableUsingJson()
    {
        $filter = new TextFilter();

        $text = <<<EOD
{{{
{
    "text": "Awesome"
}
}}}
%text%
EOD;

        $filter->setFilterConfig("frontmatter", $this->options);
        $res = $filter->parse($text, ["frontmatter", "variable"]);
        
        $this->assertEquals([
            "text" => "Awesome"
        ], $res->frontmatter);
        
        $this->assertEquals(
            "Awesome",
            $res->text
        );
    }
}
