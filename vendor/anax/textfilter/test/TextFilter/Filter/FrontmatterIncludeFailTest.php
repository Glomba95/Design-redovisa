<?php

namespace Anax\TextFilter\Filter;

use \PHPUnit\Framework\TestCase;

/**
 * Test negative
 */
class FrontmatterIncludeFailTest extends TestCase
{
    /**
     * Configuration for the frontmatter filter.
     */
    private $options = [
        "include"               => true,
        "include_base"          => __DIR__ . "/../../content",
        "frontmatter_json"      => false,
        "frontmatter_json"      => false,
        "frontmatter_yaml"      => false,
    ];



    /**
     * Include base path not set.
     *
     * @expectedException Anax\TextFilter\Filter\Exception
     */
    public function testIncludeBasePathNotSet()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#include file1.md
EOD;

        $options = $this->options;
        $options["include_base"] = null;
        $filter->parse($text, [], $options);
    }



    /**
     * Incorrect include base path.
     *
     * @expectedException Anax\TextFilter\Filter\Exception
     */
    public function testIncorrectIncludeBasePath()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#include file1.md
EOD;

        $options = $this->options;
        $options["include_base"] = "/NOSUCHDIR";
        $filter->parse($text, [], $options);
    }



    /**
     * Include file not found.
     *
     * @expectedException Anax\TextFilter\Filter\Exception
     */
    public function testIncludeFileNotFound()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#include file1_NOTFOUND.md
EOD;

        $filter->parse($text, [], $this->options);
    }
}
