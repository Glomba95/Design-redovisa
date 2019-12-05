<?php

namespace Anax\TextFilter\Filter;

use \PHPUnit\Framework\TestCase;

/**
 * Test
 */
class FrontmatterJsonFrontmatterTest extends TestCase
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
     * Document contains only frontmatter
     */
    public function testOnlyJson()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["text"]
        );
    }



    /**
     * Frontmatter should leave not trace or space.
     */
    public function testNoTraceOrSpace()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}

EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals(
            "",
            $res["text"]
        );

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
empty
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals(
            "empty",
            $res["text"]
        );
    }



    /**
     * Ignore when not frontmatter
     */
    public function testWithoutJson()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
Header 2
{
    text
}
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([], $res["frontmatter"]);

        $this->assertContains(
            "Header 2",
            $res["text"]
        );
    }



    /**
     * Frontmatter with simple value.
     */
    public function testOnlyJsonValue()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
"hi"    
}}}
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "0" => "hi",
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["text"]
        );
    }



    /**
     * Document with frontmatter followed by text
     */
    public function testOnlyJsonWithText()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
Some markdown text.
EOD;

        $res = $filter->parse($text, [], $this->options);
        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "Some markdown text.",
            $res["text"]
        );
    }



    /**
     * Frontmatter surrounded by text
     */
    public function testOnlyJsonSurroundedByText()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
Inital markdown text.
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
Some markdown text.
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "Inital markdown text.",
            $res["text"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["text"]
        );
    }




    /**
     * Document with two frontmatter parts
     */
    public function testOnlyJsonTwoParts()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
{{{
{
    "title": "hi",
    "author": "mos"
}
}}}
{{{
{
    "title": "ho"
}
}}}
EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "title" => "ho",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["text"]
        );
    }



    /**
     * Document with several frontmatter parts
     */
    public function testOnlyJsonSeveralParts()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
Inital markdown text.

{{{
{
    "title": "hi",
    "author": "mos"
}
}}}

More markdown text.

{{{
{
    "title": "ho"
}
}}}

Some markdown text.

{{{
{
    "title": "ha",
    "category": "cat"
}
}}}

EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals([
            "title" => "ha",
            "author" => "mos",
            "category" => "cat",
        ], $res["frontmatter"]);

        $this->assertContains(
            "Inital markdown text.",
            $res["text"]
        );

        $this->assertContains(
            "More markdown text.",
            $res["text"]
        );

        $this->assertContains(
            "Some markdown text.",
            $res["text"]
        );
    }
}
