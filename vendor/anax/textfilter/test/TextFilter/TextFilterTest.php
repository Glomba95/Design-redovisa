<?php

namespace Anax\TextFilter;

use \PHPUnit\Framework\TestCase;

/**
 * A testclass
 * @todo clean up testcases and move into separate classes
 */
class TextFilterTest extends TestCase
{
//     /**
//      * Supported filters.
//      */
//     private $standardFilters = [
//          'yamlfrontmatter',
//          'bbcode',
//          'clickable',
//          'markdown',
// //         'geshi',
//          'nl2br',
//          'shortcode',
//          'purify',
//          'titlefromh1',
//      ];



     /**
      * Test.
      *
      * @return void
      */
/*
    public function testSyntaxHighlightGeshiMethod()
    {
        $filter = new TextFilter();

        $text = "";
        $exp  = '<pre class="text geshi"></pre>';
        $res = $filter->parse($text, ["geshi"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
$php = "hi";
EOD;
        $exp  = '<pre class="text geshi">$php = &quot;hi&quot;;</pre>';
        $res = $filter->parse($text, ["geshi"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
$php = "hi";
EOD;

        // @codingStandardsIgnoreStart
        $exp = <<<'EOD'
<pre class="php geshi"><span class="re0">$php</span> <span class="sy0">=</span> <span class="st0">&quot;hi&quot;</span><span class="sy0">;</span></pre>
EOD;
        // @codingStandardsIgnoreEnd
        $res = $filter->syntaxHighlightGeSHi($text, "php");
        $this->assertEquals($exp, $res, "Geshi did not match");
    }
*/


    /**
     * Test.
     *
     * @return void
     */
/*
    public function testSyntaxHighlightGeshiShortCode()
    {
        $filter = new TextFilter();

        $text = <<<'EOD'
```text
```

EOD;
        $exp = <<<'EOD'
<pre class="text geshi"></pre>
EOD;
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
```
```

EOD;
        $exp = <<<'EOD'
<pre class="text geshi"></pre>
EOD;
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
```text
$php = "hi";
```

EOD;
        $exp = <<<'EOD'
<pre class="text geshi">$php = &quot;hi&quot;;
</pre>
EOD;
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
```php
$php = "hi";
```

EOD;
        // @codingStandardsIgnoreStart
        $exp = <<<'EOD'
<pre class="php geshi"><span class="re0">$php</span> <span class="sy0">=</span> <span class="st0">&quot;hi&quot;</span><span class="sy0">;</span>
</pre>
EOD;
        // @codingStandardsIgnoreEnd
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");
    }
*/



/**
 * Test.
 *
 * @return void
 */
/*
    public function testSyntaxHighlightHlJsiShortCode()
    {
        $filter = new TextFilter();

        $text = <<<'EOD'
```text
```

EOD;
        $exp = <<<'EOD'
<pre></pre>
EOD;
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
```
```

EOD;
        $exp = <<<'EOD'
<pre></pre>
EOD;
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
```text
$php = "hi";
```

EOD;
        $exp = <<<'EOD'
<pre>$php = "hi";
</pre>
EOD;
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");

        $text = <<<'EOD'
```php
$php = "hi";
```

EOD;
        // @codingStandardsIgnoreStart
        $exp = <<<'EOD'
<pre class="hljs">$php = <span class="hljs-string">"hi"</span>;
</pre>
EOD;
        // @codingStandardsIgnoreEnd
        $res = $filter->parse($text, ["shortcode"]);
        $this->assertEquals($exp, $res->text, "Geshi did not match");
    }
*/


     /**
      * Test.
      *
      * @return void
      */
    public function testTitleFromFirstH1()
    {
        $filter = new TextFilter();

        $text = "";
        $res = $filter->parse($text, ["titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertNull($title, "Title should be null");

        $text = "<h1>My title</h1>";
        $exp = "My title";
        $res = $filter->parse($text, ["titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");

        $text = "<h1><a href=''>My title</a></h1>";
        $exp = "My title";
        $res = $filter->parse($text, ["titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");

        $text = "<h1 class=''>My title</h1>";
        $exp = "My title";
        $res = $filter->parse($text, ["titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");

        $text = <<<EOD
{{{
{
    "title": "JSON title"
}
}}}
<h1 class=''>My title</h1>
EOD;
        $exp = "JSON title";
        $res = $filter->parse($text, ["titlefromh1", "jsonfrontmatter"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");

        $exp = "JSON title";
        $res = $filter->parse($text, ["jsonfrontmatter", "titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");

        $text = <<<EOD
{{{
{
    "title": "JSON title"
}
}}}
My title
=================================

This is the index page.
EOD;
        $exp = "JSON title";
        $res = $filter->parse($text, ["jsonfrontmatter", "markdown", "titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");

        $text = <<<EOD
{{{
{
    "title-no": "JSON title"
}
}}}
My title
=================================

This is the index page.
EOD;
        $exp = "My title";
        $res = $filter->parse($text, ["jsonfrontmatter", "markdown", "titlefromh1"]);
        $title = $res->frontmatter["title"];
        $this->assertEquals($exp, $title, "Title missmatch");
    }



     /**
      * Test.
      *
      * @expectedException /Mos/TextFilter/Exception
      *
      * @return void
      */
    public function testJsonFrontMatterException()
    {
        $filter = new TextFilter();

        $text = <<<EOD
{{{

}}}
EOD;
        $filter->parse($text, ["jsonfrontmatter"]);
    }



     /**
      * Test.
      *
      * @return void
      */
    public function testJsonFrontMatter()
    {
        $filter = new TextFilter();

        $text = "";
        $res = $filter->parse($text, ["jsonfrontmatter"]);
        $this->assertEmpty($res->frontmatter, "Frontmatter should be empty");
        $this->assertEmpty($res->text, "Text should be empty");

        $text = <<<EOD
{{{
}}}

EOD;
        $res = $filter->parse($text, ["jsonfrontmatter"]);
        $this->assertEmpty($res->frontmatter, "Frontmatter should be empty");
        $this->assertEmpty($res->text, "Text should be empty");

        $txt = "TEXT";
        $text = <<<EOD
{{{
{
    "key": "value"
}
}}}
$txt
EOD;
        $res = $filter->parse($text, ["jsonfrontmatter"]);
        $this->assertEquals(
            $res->frontmatter,
            [
                "key" => "value"
            ],
            "Frontmatter should be empty"
        );
        $this->assertEquals($txt, $res->text, "Text missmatch");
    }



    /**
     * Test.
     *
     * @expectedException /Mos/TextFilter/Exception
     *
     * @return void
     */
    public function testYamlFrontMatterException()
    {
        if (!function_exists("yaml_parse")) {
            return;
        }

        $filter = new TextFilter();

        $text = <<<EOD
---

---
EOD;
        $filter->parse($text, ["yamlfrontmatter"]);
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testYamlFrontMatter()
    {
        if (!function_exists("yaml_parse")) {
            return;
        }

        $filter = new TextFilter();

        $text = "";
        $res = $filter->parse($text, ["yamlfrontmatter"]);
        $this->assertEmpty($res->frontmatter, "Frontmatter should be empty");
        $this->assertEmpty($res->text, "Text should be empty");

        $text = <<<EOD
---
...

EOD;
        $res = $filter->parse($text, ["yamlfrontmatter"]);
        $this->assertEmpty($res->frontmatter, "Frontmatter should be empty");
        $this->assertEmpty($res->text, "Text should be empty");

        $txt = "TEXT";
        $text = <<<EOD
---
key: value
...
$txt
EOD;
        $res = $filter->parse($text, ["yamlfrontmatter"]);
        $this->assertEquals(
            $res->frontmatter,
            [
                "key" => "value"
            ],
            "Frontmatter not matching"
        );
        $this->assertEquals($txt, $res->text, "Text missmatch");

        $text = <<<EOD
---
key1: value1
key2: This is a long sentence.
...
My Article
=================================

This is an example on writing text and adding a YAML frontmatter.

Subheading
---------------------------------

More text.

EOD;
        $res = $filter->parse($text, ["yamlfrontmatter", "markdown"]);
        $this->assertEquals(
            $res->frontmatter,
            [
                "key1" => "value1",
                "key2" => "This is a long sentence."
            ],
            "Frontmatter not matching"
        );

        $text = <<<EOD
My Article
=================================

This is an example on writing text and adding a YAML frontmatter.

Subheading
---------------------------------

More text.

EOD;
        $res = $filter->parse($text, ["yamlfrontmatter", "markdown"]);
        $this->assertEmpty($res->frontmatter, "Frontmatter should be empty");
    }



    // /**
    //  * Test.
    //  *
    //  * @return void
    //  */
    // public function testGetFilters()
    // {
    //     $filter = new TextFilter();
    //
    //     $filters = $filter->getFilters();
    //     $res = array_diff($this->standardFilters, $filters);
    //     $this->assertTrue(empty($res), "Missmatch standard filters.");
    // }




    /**
     * Test.
     *
     * @return void
     */
    public function testHasFilter()
    {
        $filter = new TextFilter();

        $res = $filter->hasFilter("markdown");
        $this->assertTrue($res, "Missmatch has filters.");
    }




    /**
     * Test.
     *
     * @expectedException /Mos/TextFilter/Exception
     *
     * @return void
     */
    public function testHasFilterException()
    {
        $filter = new TextFilter();

        $filter->hasFilter("NOT EXISTING");
    }




    /**
     * Test.
     *
     * @return void
     */
    public function testPurifier()
    {
        $filter = new TextFilter();

        $text = "Header\n=========";
        $exp  = "<h1>Header</h1>\n";
        $res = $filter->parse($text, ["markdown", "purify"]);
        $this->assertEquals($exp, $res->text, "Purify failed");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testMarkdown()
    {
        $filter = new TextFilter();

        $html = "Header\n=========";
        $exp  = "<h1>Header</h1>\n";
        $res = $filter->doFilter($html, "markdown");
        $this->assertEquals($exp, $res, "Markdown <h1> failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testSmartyPants()
    {
        $filter = new TextFilter();

        $html = "...";
        $exp  = "<p>&#8230;</p>\n";
        $res = $filter->doFilter($html, "markdown");
        $this->assertEquals($exp, $res, "SmartyPants elippsis failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testMarkdownAndBBCode()
    {
        $filter = new TextFilter();

        $html = "Header[b]text[/b]\n=========";
        $exp  = "<h1>Header<strong>text</strong></h1>\n";
        $res = $filter->doFilter($html, "markdown, bbcode");
        $this->assertEquals($exp, $res, "Markdown <h1> failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testMarkdownAndBBCodeAsArray()
    {
        $filter = new TextFilter();

        $html = "Header[b]text[/b]\n=========";
        $exp  = "<h1>Header<strong>text</strong></h1>\n";
        $res = $filter->doFilter($html, ["markdown", "bbcode"]);
        $this->assertEquals($exp, $res, "Markdown <h1> failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testMarkdownArray()
    {
        $filter = new TextFilter();

        $html = "Header\n=========";
        $exp  = "<h1>Header</h1>\n";
        $res = $filter->doFilter($html, ["markdown"]);
        $this->assertEquals($exp, $res, "Markdown <h1> failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testUppercase()
    {
        $filter = new TextFilter();

        $html = "Header\n=========";
        $exp  = "<h1>Header</h1>\n";
        $res = $filter->doFilter($html, "MARKDOWN");
        $this->assertEquals($exp, $res, "Markdown <h1> failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testBBCode()
    {
        $filter = new TextFilter();

        $html = "[b]text[/b]";
        $exp  = "<strong>text</strong>";
        $res = $filter->doFilter($html, "bbcode");
        $this->assertEquals($exp, $res, "BBCode [b] failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testClickable()
    {
        $filter = new TextFilter();

        $html = "http://example.com/humans.txt";
        $exp  = "<a href='$html'>$html</a>";
        $res = $filter->doFilter($html, "clickable");
        $this->assertEquals($exp, $res, "clickable failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testNl2Br()
    {
        $filter = new TextFilter();

        $html = "hej\nhej";
        $exp  = "hej<br />\nhej";
        $res = $filter->doFilter($html, "nl2br");
        $this->assertEquals($exp, $res, "nl2br failed: '$res'");
    }



    /**
     * Test.
     *
     * @return void
     */
    public function testShortCodeFigure()
    {
        $filter = new TextFilter();

        $src = "/img/me.png";
        $caption = "This is me.";
        
        $html = <<<EOD
[FIGURE src=$src caption="$caption"]
EOD;

        $exp  = <<<EOD
<figure class="figure">
<a href="$src"><img src="$src" alt="$caption"/></a>
<figcaption markdown=1>$caption</figcaption>
</figure>
EOD;
        $res = $filter->doFilter($html, "shortcode");
        $this->assertEquals($exp, $res, "shortcode failed: '$res'");
    }
}
