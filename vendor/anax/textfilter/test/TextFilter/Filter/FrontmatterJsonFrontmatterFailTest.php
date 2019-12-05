<?php

namespace Anax\TextFilter\Filter;

use \PHPUnit\Framework\TestCase;

/**
 * Test negative
 */
class FrontmatterJsonFrontmatterFailTest extends TestCase
{
    /**
     * Configuration for the frontmatter filter.
     */
    private $options = [
        "include"               => false,
        "frontmatter_json"      => true,
        "frontmatter_yaml"      => false,
    ];



    /**
     * Frontmatter without end marker.
     *
     * @expectedException Anax\TextFilter\Filter\Exception
     */
    public function testJsonWithoutEndMarker()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
EOD;

        $filter->parse($text, [], $this->options);
    }



    /**
     * Frontmatter with error.
     *
     * @expectedException Anax\TextFilter\Filter\Exception
     */
    public function testJsonWithError()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
{
    "title": "hi"
    "author": "mos"
}
}}}
EOD;

        $filter->parse($text, [], $this->options);
    }
}
