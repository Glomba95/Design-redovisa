<?php

namespace Anax\TextFilter\Filter;

use \PHPUnit\Framework\TestCase;

/**
 * Test
 */
class FrontmatterYamlFrontmatterTest extends TestCase
{
    /**
     * Configuration for the frontmatter filter.
     */
    private $options = [
        "include"               => false,
        "frontmatter_json"      => false,
        "frontmatter_yaml"      => true,
        "yaml_parser_pecl"      => true,
        "yaml_parser_symfony"   => true,
        "yaml_parser_spyc"      => true,
    ];



    /**
     * Document contains only frontmatter
     */
    public function testOnlyYaml()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
---
title: hi
author: mos
---
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
---
title: hi
author: mos
---

EOD;

        $res = $filter->parse($text, [], $this->options);

        $this->assertEquals(
            "",
            $res["text"]
        );

        $text = <<<EOD
---
title: hi
author: mos
---
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
    public function testWithoutYaml()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
Header 2
--------
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
    public function testOnlyYamlValue()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
---
hi
---
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
    public function testOnlyYamlWithText()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
---
title: hi
author: mos
---
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
    public function testOnlyYamlSurroundedByText()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
Inital markdown text.
---
title: hi
author: mos
---
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
    public function testOnlyYamlTwoParts()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
---
title: hi
author: mos
---
---
title: ho
---
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
    public function testOnlyYamlSeveralParts()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
Inital markdown text.

---
title: hi
author: mos
---

More markdown text.

---
title: ho
---

Some markdown text.

---
title: ha
category: cat
---

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



    /**
     * Test the symfony parser.
     */
    public function testYamlParserSymfony()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
---
title: hi
author: mos
---
EOD;

        $options = $this->options;
        $options["yaml_parser_pecl"] = false;
        $options["yaml_parser_symfony"] = true;
        $options["yaml_parser_spyc"] = false;
        $res = $filter->parse($text, [], $options);

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
     * Test the spyc parser.
     */
    public function testYamlParserSpyc()
    {
        $filter = new Frontmatter();

        $text = <<<EOD
---
title: hi
author: mos
---
EOD;

        $options = $this->options;
        $options["yaml_parser_pecl"] = false;
        $options["yaml_parser_symfony"] = false;
        $options["yaml_parser_spyc"] = true;
        $res = $filter->parse($text, [], $options);

        $this->assertEquals([
            "title" => "hi",
            "author" => "mos"
        ], $res["frontmatter"]);

        $this->assertContains(
            "",
            $res["text"]
        );
    }
}
