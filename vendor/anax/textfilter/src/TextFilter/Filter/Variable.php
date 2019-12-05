<?php

namespace Anax\TextFilter\Filter;

use \Symfony\Component\Yaml\Yaml;

/**
 * Filter to replace variable expressions like %var% with the respective value
 * defined in the frontmatter.
 */
class Variable implements FilterInterface
{
    /**
     * @var array $frontmatter parsed as frontmatter, accumulated when parsing
     *                         the text.
     */
    protected $frontmatter;



    /**
     * Parse the text through the filter and do what the filter does,
     * return the resulting text and with some optional additional details,
     * all wrapped in an key-value array.
     *
     * @param string $text        to parse.
     * @param array  $frontmatter optional to use while parsing.
     * @param array  $options     custom options to use while parsing.
     *
     * @return array with the resulting text and frontmatter.
     */
    public function parse($text, array $frontmatter, array $options = [])
    {
        $this->frontmatter = $frontmatter;
        $text = $this->parseForVariable($text);

        return [
            "text" => $text,
            "frontmatter" => $this->frontmatter,
        ];
    }



    /**
     * Detect variable expressions %var% and replace with their values, if the
     * variable is defined.
     *
     * @param string $text to parse for variable expressions..
     *
     * @return string as parsed text where variable are replaced with values.
     */
    protected function parseForVariable($text)
    {
        $text = preg_replace_callback(
            "/(%[a-zA-Z_$][\w$]*%)/",
            [$this, "getVariableValue"],
            $text
        );

        return $text;
    }



    /**
     * Detect and extract inline variables.
     *
     * @param string $text to parse.
     *
     * @return boolean|void true when block is found and parsed, else void.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function getVariableValue(array $matches)
    {
        $res = $matches[0];
        $variable = substr($matches[0], 1, -1);

        if (isset($this->frontmatter[$variable])) {
            $res = $this->frontmatter[$variable];
        }

        return $res;
    }
}
