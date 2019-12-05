<?php

namespace Anax\TextFilter;

/**
 * Extract frontmatter from text and pass text through a set of filter to
 * format and extract information from the text.
 */
class TextFilter
{
    use TTextUtilities,
        TShortcode;



    /**
     * Supported filters.
     */
    private $filters = [
        // Supported since 1.2
        "frontmatter" => "Anax\TextFilter\Filter\Frontmatter",
        "variable"    => "Anax\TextFilter\Filter\Variable",

        // Supported before 1.2
        "bbcode" => "",
        "clickable" => "",
        "shortcode" => "",
        "markdown" => "",
//        "geshi",
        "nl2br" => "",
        "htmlentities" => "",
        "purify" => "",
        "titlefromh1" => "",
        "titlefromheader" => "",
        "anchor4Header" => "",

        // @deprecated after 1.2
        "jsonfrontmatter" => "", // @deprecated replaced with frontmatter since 1.2
        "yamlfrontmatter" => "", // @deprecated replaced with frontmatter since 1.2
     ];



     /**
      * Current document parsed.
      */
    private $current;



    /**
     * Configuration for individual filters.
     */
    private $config = [];



    /**
     * Hold meta information for filters to use.
     */
    private $meta = [];



    /**
     * Call each filter.
     *
     * @deprecated deprecated since version 1.2 mosbth/textfilter in favour of parse().
     *
     * @param string       $text    the text to filter.
     * @param string|array $filters as comma separated list of filter,
     *                              or filters sent in as array.
     *
     * @return string the formatted text.
     */
    public function doFilter($text, $filters)
    {
        // Define all valid filters with their callback function.
        $callbacks = [
            'bbcode'    => 'bbcode2html',
            'clickable' => 'makeClickable',
            'shortcode' => 'shortCode',
            'markdown'  => 'markdown',
            'nl2br'     => 'nl2br',
            'purify'    => 'purify',
        ];
    
        // Make an array of the comma separated string $filters
        if (is_array($filters)) {
            $filter = $filters;
        } else {
            $filters = strtolower($filters);
            $filter = preg_replace('/\s/', '', explode(',', $filters));
        }
    
        // For each filter, call its function with the $text as parameter.
        foreach ($filter as $key) {
            if (!isset($callbacks[$key])) {
                throw new Exception("The filter '$filters' is not a valid filter string due to '$key'.");
            }
            $text = call_user_func_array([$this, $callbacks[$key]], [$text]);
        }
    
        return $text;
    }



    /**
     * Set configuration for a certain filter.
     *
     * @param string $filter the label of the filter to set configuration for.
     * @param array  $config the configuration as an array.
     *
     * @return void
     */
    public function setFilterConfig($filter, $config)
    {
        if (!$this->hasFilter($filter)) {
            throw new Exception("No such filter '$filter' exists.");
        }

        $this->config[$filter] = $config;
    }



    /**
     * Get configuration for a certain filter.
     *
     * @param string $filter the label of the filter to get configuration for.
     * @param array  $config the configuration as an array.
     *
     * @return array the configuration as an array or empty array.
     */
    public function getFilterConfig($filter)
    {
        if (!$this->hasFilter($filter)) {
            throw new Exception("No such filter '$filter' exists.");
        }

        return isset($this->config[$filter])
            ? $this->config[$filter]
            : [];
    }



    /**
     * Set meta information that some filters can use.
     *
     * @param array $meta values for filters to use.
     *
     * @return void
     */
    public function setMeta($meta)
    {
        return $this->meta = $meta;
    }



    /**
     * Return an array of all filters supported.
     *
     * @return array with strings of filters supported.
     */
    public function getFilters()
    {
        return $this->filters;
    }



    /**
     * Check if filter is supported.
     *
     * @param string $filter to use.
     *
     * @throws mos/TextFilter/Exception  when filter does not exists.
     *
     * @return boolean true if filter exists, false othwerwise.
     */
    public function hasFilter($filter)
    {
        return array_key_exists($filter, $this->filters);
    }



    /**
     * Add array items to frontmatter.
     *
     * @deprecated since 1.2, replaced with filter\Frontmatter.
     *
     * @param array|null $matter key value array with items to add
     *                           or null if empty.
     *
     * @return $this
     */
    private function addToFrontmatter($matter)
    {
        if (empty($matter) || !is_array($matter)) {
            return $this;
        }

        if (is_null($this->current->frontmatter)) {
            $this->current->frontmatter = [];
        }

        $this->current->frontmatter = array_merge($this->current->frontmatter, $matter);
        return $this;
    }



    /**
     * Call a specific filter and store its details.
     *
     * @param string $filter to use.
     *
     * @throws mos/TextFilter/Exception when filter does not exists.
     *
     * @return string the formatted text.
     */
    private function parseFactory($filter)
    {
        // Define single tasks filter with a callback.
        $callbacks = [
            "bbcode"    => "bbcode2html",
            "clickable" => "makeClickable",
            "shortcode" => "shortCode",
            "markdown"  => "markdown",
            //"geshi"     => "syntaxHighlightGeSHi",
            "nl2br"     => "nl2br",
            "htmlentities" => "htmlentities",
            "purify"    => "purify",
            'anchor4Header' => 'createAnchor4Header',
        ];

        // Do the specific filter
        $text = $this->current->text;
        $frontmatter = $this->current->frontmatter;
        switch ($filter) {
            case "frontmatter":
            case "variable":
                $filterObject = new $this->filters[$filter]();
                $config = $this->getFilterConfig("$filter");
                $res = $filterObject->parse($text, $frontmatter, $config);
                $this->current->text = $res["text"];
                $this->current->frontmatter = $res["frontmatter"];
                break;

            case "jsonfrontmatter":
                $res = $this->jsonFrontMatter($text);
                $this->current->text = $res["text"];
                $this->addToFrontmatter($res["frontmatter"]);
                break;

            case "yamlfrontmatter":
                $res = $this->yamlFrontMatter($text);
                $this->current->text = $res["text"];
                $this->addToFrontmatter($res["frontmatter"]);
                break;

            case "titlefromh1":
                $title = $this->getTitleFromFirstH1($text);
                $this->current->text = $text;
                if (!isset($this->current->frontmatter["title"])) {
                    $this->addToFrontmatter(["title" => $title]);
                }
                break;

            case "titlefromheader":
                $title = $this->getTitleFromFirstHeader($text);
                $this->current->text = $text;
                if (!isset($this->current->frontmatter["title"])) {
                    $this->addToFrontmatter(["title" => $title]);
                }
                break;

            case "bbcode":
            case "clickable":
            case "shortcode":
            case "markdown":
            //case "geshi":
            case "nl2br":
            case "htmlentities":
            case "purify":
            case "anchor4Header":
                $this->current->text = call_user_func_array(
                    [$this, $callbacks[$filter]],
                    [$text]
                );
                break;

            default:
                throw new Exception("The filter '$filter' is not a valid filter     string.");
        }
    }



    /**
     * Call each filter and return array with details of the formatted content.
     *
     * @param string $text   the text to filter.
     * @param array  $filter array of filters to use.
     *
     * @throws Anax\TextFilter\Exception  when filter does not exists.
     *
     * @return array with the formatted text and additional details.
     */
    public function parse($text, $filter)
    {
        $this->current = new \stdClass();
        $this->current->frontmatter = [];
        $this->current->text = $text;

        foreach ($filter as $key) {
            $this->parseFactory($key);
        }

        $this->current->text = $this->getUntilStop($this->current->text);

        return $this->current;
    }



    /**
     * Add excerpt as short version of text if available.
     *
     * @param object &$current same structure as returned by parse().
     *
     * @return void.
     */
    public function addExcerpt($current)
    {
        list($excerpt, $hasMore) = $this->getUntilMore($current->text);
        $current->excerpt = $excerpt;
        $current->hasMore = $hasMore;
    }



    /**
     * Extract front matter from text.
     *
     * @deprecated since 1.2, replaced with filter\Frontmatter.
     *
     * @param string $text       the text to be parsed.
     * @param string $startToken the start token.
     * @param string $stopToken  the stop token.
     *
     * @return array with the formatted text and the front matter.
     */
    private function extractFrontMatter($text, $startToken, $stopToken)
    {
        $tokenLength = strlen($startToken);

        $start = strpos($text, $startToken);
        // Is a valid start?
        if ($start !== false && $start !== 0) {
            if ($text[$start - 1] !== "\n") {
                $start = false;
            }
        }

        $frontmatter = null;
        if ($start !== false) {
            $stop = strpos($text, $stopToken, $tokenLength - 1);

            if ($stop !== false && $text[$stop - 1] === "\n") {
                $length = $stop - ($start + $tokenLength);

                $frontmatter = substr($text, $start + $tokenLength, $length);
                $textStart = substr($text, 0, $start);
                $text = $textStart . substr($text, $stop + $tokenLength);
            }
        }

        return [$text, $frontmatter];
    }



    /**
     * Extract JSON front matter from text.
     *
     * @deprecated since 1.2, replaced with filter\Frontmatter.
     *
     * @param string $text the text to be parsed.
     *
     * @return array with the formatted text and the front matter.
     */
    public function jsonFrontMatter($text)
    {
        list($text, $frontmatter) = $this->extractFrontMatter($text, "{{{\n", "}}}\n");

        if (!empty($frontmatter)) {
            $frontmatter = json_decode($frontmatter, true);

            if (is_null($frontmatter)) {
                throw new Exception("Failed parsing JSON frontmatter.");
            }
        }

        return [
            "text" => $text,
            "frontmatter" => $frontmatter
        ];
    }



    /**
     * Extract YAML front matter from text.
     *
     * @deprecated since 1.2, replaced with filter\Frontmatter.
     *
     * @param string $text the text to be parsed.
     *
     * @return array with the formatted text and the front matter.
     */
    public function yamlFrontMatter($text)
    {
        list($text, $frontmatter) = $this->extractFrontMatter($text, "---\n", "...\n");

        if (!empty($frontmatter)) {
            $frontmatter = $this->yamlParse("---\n$frontmatter...\n");
        }

        return [
            "text" => $text,
            "frontmatter" => $frontmatter
        ];
    }



    /**
     * Extract YAML front matter from text, use one of several available
     * implementations of a YAML parser.
     *
     * @deprecated since 1.2, replaced with filter\Frontmatter.
     *
     * @param string $text the text to be parsed.
     *
     * @throws: Exception when parsing frontmatter fails.
     *
     * @return array with the formatted text and the front matter.
     */
    public function yamlParse($text)
    {
        if (function_exists("yaml_parse")) {
            // Prefer php5-yaml extension
            $parsed = yaml_parse($text);

            if ($parsed === false) {
                throw new Exception("Failed parsing YAML frontmatter.");
            }

            return $parsed;
        }

        if (method_exists("Symfony\Component\Yaml\Yaml", "parse")) {
            // symfony/yaml
            $parsed = \Symfony\Component\Yaml\Yaml::parse($text);
            return $parsed;
        }

        if (function_exists("spyc_load")) {
            // mustangostang/spyc
            $parsed = spyc_load($text);
            return $parsed;
        }

        throw new Exception("Could not find support for YAML.");
    }



    /**
     * Get the title from the first H1.
     *
     * @param string $text the text to be parsed.
     *
     * @return string|null with the title, if its found.
     */
    public function getTitleFromFirstH1($text)
    {
        $matches = [];
        $title = null;

        if (preg_match("#<h1.*?>(.*)</h1>#", $text, $matches)) {
            $title = strip_tags($matches[1]);
        }

        return $title;
    }



    /**
     * Get the title from the first header.
     *
     * @param string $text the text to be parsed.
     *
     * @return string|null with the title, if its found.
     */
    public function getTitleFromFirstHeader($text)
    {
        $matches = [];
        $title = null;

        if (preg_match("#<h[1-6].*?>(.*)</h[1-6]>#", $text, $matches)) {
            $title = strip_tags($matches[1]);
        }

        return $title;
    }



    /**
     * Helper, BBCode formatting converting to HTML.
     *
     * @param string $text The text to be converted.
     *
     * @return string the formatted text.
     *
     * @link http://dbwebb.se/coachen/reguljara-uttryck-i-php-ger-bbcode-formattering
     */
    public function bbcode2html($text)
    {
        $search = [
            '/\[b\](.*?)\[\/b\]/is',
            '/\[i\](.*?)\[\/i\]/is',
            '/\[u\](.*?)\[\/u\]/is',
            '/\[img\](https?.*?)\[\/img\]/is',
            '/\[url\](https?.*?)\[\/url\]/is',
            '/\[url=(https?.*?)\](.*?)\[\/url\]/is'
        ];

        $replace = [
            '<strong>$1</strong>',
            '<em>$1</em>',
            '<u>$1</u>',
            '<img src="$1" />',
            '<a href="$1">$1</a>',
            '<a href="$1">$2</a>'
        ];

        return preg_replace($search, $replace, $text);
    }



    /**
     * Make clickable links from URLs in text.
     *
     * @param string $text the text that should be formatted.
     *
     * @return string with formatted anchors.
     *
     * @link http://dbwebb.se/coachen/lat-php-funktion-make-clickable-automatiskt-skapa-klickbara-lankar
     */
    public function makeClickable($text)
    {
        return preg_replace_callback(
            '#\b(?<![href|src]=[\'"])https?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
            function ($matches) {
                return "<a href='{$matches[0]}'>{$matches[0]}</a>";
            },
            $text
        );
    }



    /**
     * Syntax highlighter using GeSHi http://qbnz.com/highlighter/.
     *
     * @param string $text     text to be converted.
     * @param string $language which language to use for highlighting syntax.
     *
     * @return string the formatted text.
     */
     /*
    public function syntaxHighlightGeSHi($text, $language = "text")
    {
        $language = $language ?: "text";
        //$language = ($language === 'html') ? 'html4strict' : $language;
        $language = ($language === 'html') ? 'html5' : $language;

        $geshi = new \GeSHi($text, $language);
        $geshi->set_overall_class('geshi');
        $geshi->enable_classes('geshi');
        //$geshi->set_header_type(GESHI_HEADER_PRE_VALID);
        //$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        $code = $geshi->parse_code();

        //echo "<pre>$language\n$code\n", $geshi->get_stylesheet(false) , "</pre>"; exit;

        // Replace last &nbsp;</pre>, -strlen("&nbsp;</pre>") == 12
        $length = strlen("&nbsp;</pre>");
        if (substr($code, -$length) == "&nbsp;</pre>") {
            $code = substr_replace($code, "</pre>", -$length);
        }

        return $code;
    }
*/



    /**
     * Syntax highlighter using highlight.php, a port of highlight.js
     * https://packagist.org/packages/scrivo/highlight.php.
     *
     * @param string $text     text to be converted.
     * @param string $language which language to use for highlighting syntax.
     *
     * @return string the formatted text.
     */
    public function syntaxHighlightJs($text, $language = "text")
    {
        if ($language === "text" || empty($language)) {
            return "<pre class=\"hljs\">" . htmlentities($text) . "</pre>";
        }

        $highlight = new \Highlight\Highlighter();
        $res = $highlight->highlight($language, $text);

        return "<pre class=\"hljs\">$res->value</pre>";
    }



    /**
     * Format text according to HTML Purifier.
     *
     * @param string $text that should be formatted.
     *
     * @return string as the formatted html-text.
     */
    public function purify($text)
    {
        $config   = \HTMLPurifier_Config::createDefault();
        $config->set("Cache.DefinitionImpl", null);
        //$config->set('Cache.SerializerPath', '/home/user/absolute/path');

        $purifier = new \HTMLPurifier($config);
    
        return $purifier->purify($text);
    }



    /**
     * Format text according to Markdown syntax.
     *
     * @param string $text the text that should be formatted.
     *
     * @return string as the formatted html-text.
     */
    public function markdown($text)
    {
        $text = \Michelf\MarkdownExtra::defaultTransform($text);
        $text = \Michelf\SmartyPantsTypographer::defaultTransform(
            $text,
            "2"
        );
        return $text;
    }



    /**
     * For convenience access to nl2br
     *
     * @param string $text text to be converted.
     *
     * @return string the formatted text.
     */
    public function nl2br($text)
    {
        return nl2br($text);
    }



    /**
     * For convenience access to htmlentities
     *
     * @param string $text text to be converted.
     *
     * @return string the formatted text.
     */
    public function htmlentities($text)
    {
        return htmlentities($text);
    }
}
