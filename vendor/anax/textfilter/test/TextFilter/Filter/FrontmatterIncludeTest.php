<?php

namespace Anax\TextFilter\Filter;

use \PHPUnit\Framework\TestCase;

/**
 * Test
 */
class FrontmatterIncludeTest extends TestCase
{
    /**
     * Configuration for the frontmatter filter.
     */
    private $options = [
        "include"               => true,
        "include_base"          => __DIR__ . "/../../content",
        "frontmatter_json"      => false,
        "frontmatter_yaml"      => true,
    ];



    /**
     * No include directive.
     */
    public function testNoInclude()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#
# include file1.md
#includefile1.md
# A H1 header in Markdown

EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertContains(
            "# include file1.md",
            $res["text"]
        );

        $this->assertContains(
            "#includefile1.md",
            $res["text"]
        );

        $this->assertContains(
            "# A H1 header in Markdown",
            $res["text"]
        );
    }



    /**
     * Include single file with text and frontmatter.
     */
    public function testIncludeOneFile()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#include file1.md
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "first" => "file1",
            "title" => "file1",
        ], $res["frontmatter"]);
        
        $this->assertContains(
            "file1",
            $res["text"]
        );
    }



    /**
     * Include two files with text and frontmatter.
     */
    public function testIncludeTwoFiles()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#include file1.md
#include file2.md
Some markdown text.
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "first" => "file1",
            "title" => "file2"
        ], $res["frontmatter"]);
        
        $this->assertContains(
            "file1",
            $res["text"]
        );

        $this->assertContains(
            "file2",
            $res["text"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["text"]
        );
    }


    /**
     * Include a file which includes two other files.
     */
    public function testIncludeFileWhichIncludes()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
#include file3.md
Some markdown text.
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "first" => "file1",
            "title" => "file2"
        ], $res["frontmatter"]);
        
        $this->assertContains(
            "file1",
            $res["text"]
        );

        $this->assertContains(
            "file2",
            $res["text"]
        );

        $this->assertContains(
            "file3",
            $res["text"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["text"]
        );
    }
}
