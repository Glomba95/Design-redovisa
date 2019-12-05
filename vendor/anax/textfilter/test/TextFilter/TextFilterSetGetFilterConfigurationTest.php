<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * Test that each filter can have its own configuration.
 */
class TextFilterSetGetFilterConfigurationTest extends TestCase
{
    /**
     * Check that a filter can be set and get using empty configuration.
     */
    public function testSetEmptyConfigForFilter()
    {
        $filter = new TextFilter();
        $filter->setFilterConfig("frontmatter", []);
        $res = $filter->getFilterConfig("frontmatter");
        $this->assertEmpty($res);
        $this->assertInternalType("array", $res);
    }



    /**
     * Check that a filter can be set and get using configuration.
     */
    public function testSetConfigForFilter()
    {
        $filter = new TextFilter();
        $filter->setFilterConfig("frontmatter", ["a" => "b"]);
        $res = $filter->getFilterConfig("frontmatter");
        $this->assertArrayHasKey("a", $res);
        $this->assertEquals("b", $res["a"]);
    }



    /**
     * Check that the filter always is empty to start with.
     */
    public function testGetDefaultConfigForFilter()
    {
        $filter = new TextFilter();
        $res = $filter->getFilterConfig("frontmatter");
        $this->assertEmpty($res);
        $this->assertInternalType("array", $res);
    }
}
