<?php

namespace Anax\TextFilter\Filter;

/**
 * Interface which a filter must implement.
 */
interface FilterInterface
{
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
    public function parse($text, array $frontmatter, array $options = []);
}
