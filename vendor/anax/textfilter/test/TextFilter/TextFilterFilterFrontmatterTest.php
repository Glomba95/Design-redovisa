<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * A testclass
 *
 */
class TextFilterFilterFrontmatterTest extends TestCase
{
    /**
     * Configuration for the frontmatter filter.
     */
    private $options = [
        "include"               => true,
        "include_base"          => __DIR__ . "/../content",
        "frontmatter_json"      => true,
        "frontmatter_yaml"      => true,
    ];



    /**
     * Test having current filter.
     */
    public function testHavingCurrentFilter()
    {
        $filter = new TextFilter();

        $this->assertTrue($filter->hasFilter("frontmatter"));
    }



    /**
     * Test including files
     */
    public function testIncludingFiles()
    {
        $filter = new TextFilter();

        $text = <<<EOD
#include file1.md
EOD;

        $filter->setFilterConfig("frontmatter", $this->options);
        $res = $filter->parse($text, ["frontmatter"]);
        
        $this->assertEquals([
            "title" => "file1",
            "first" => "file1"
        ], $res->frontmatter);
        
        $this->assertContains(
            "file1",
            $res->text
        );
    }



    /**
     * Test with frontmatter yaml
     */
    public function testFrontmatterYaml()
    {
        $filter = new TextFilter();

        $text = <<<EOD
---
title: frontmatter
---
text
EOD;

        $filter->setFilterConfig("frontmatter", $this->options);
        $res = $filter->parse($text, ["frontmatter"]);
        
        $this->assertEquals([
            "title" => "frontmatter"
        ], $res->frontmatter);
        
        $this->assertContains(
            "text",
            $res->text
        );
    }



    /**
     * Test with frontmatter json
     */
    public function testFrontmatterJson()
    {
        $filter = new TextFilter();

        $text = <<<EOD
{{{
{
    "title": "frontmatter"
}
}}}
text
EOD;

        $filter->setFilterConfig("frontmatter", $this->options);
        $res = $filter->parse($text, ["frontmatter"]);
        
        $this->assertEquals([
            "title" => "frontmatter"
        ], $res->frontmatter);
        
        $this->assertContains(
            "text",
            $res->text
        );
    }



    /**
     * Test with frontmatter include, yaml and json
     */
    public function testFrontmatter()
    {
        $filter = new TextFilter();

        $text = <<<EOD
#include file5.md
Some markdown text.
EOD;

        $filter->setFilterConfig("frontmatter", $this->options);
        $res = $filter->parse($text, ["frontmatter"]);

        $this->assertEquals([
            "first" => "file1",
            "title" => "file4",
            "more" => "file4",
        ], $res->frontmatter);

        $this->assertContains(
            "file1",
            $res->text
        );

        $this->assertContains(
            "file2",
            $res->text
        );

        $this->assertContains(
            "file3",
            $res->text
        );

        $this->assertContains(
            "file4",
            $res->text
        );

        $this->assertContains(
            "file5",
            $res->text
        );

        $this->assertContains(
            "Some markdown text.",
            $res->text
        );
    }
}
