<?php

namespace Anax\TextFilter\Filter;

use \Symfony\Component\Yaml\Yaml;

/**
 * Filter and format content.
 */
class Frontmatter implements FilterInterface
{
    /**
     * @var array $config with options on how to parse text.
     */
    protected $config;



    /**
     * @var array $lines text split into lines.
     */
    protected $lines;



    /**
     * @var array $lineNumber current line number being parsed.
     */
    protected $lineNumber;



    /**
     * @var integer $linesRemoved keep track on how many lines is removed.
     */
    protected $linesRemoved;



    /**
     * @var array $frontmatter parsed as frontmatter, accumulated when parsing
     *                         the text.
     */
    protected $frontmatter;



    /**
     * @var array $blockTypes with details on block to detect.
     */
    protected $blockTypes = [
        "#" => ["Include"],
        "-" => ["YamlFrontmatter"],
        "{" => ["JsonFrontmatter"],
    ];



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
        $config = [
            "include"               => true,
            "include_base"          => null,
            "frontmatter_json"      => true,
            "frontmatter_yaml"      => true,
            "yaml_parser_pecl"      => true,
            "yaml_parser_symfony"   => true,
            "yaml_parser_spyc"      => true,
        ];
        $this->config = array_merge($config, $options);

        if ($this->config["include"]
            && !is_dir($this->config["include_base"])) {
            throw new Exception("Include base is not a readable directory.");
        }

        // Unify lineendings
        $text = str_replace(array("\r\n", "\r"), "\n", $text);
        $this->lines = explode("\n", $text);
        $this->lineNumber = 0;
        $this->linesRemoved = 0;     // Only needed for log?
        $this->frontmatter = $frontmatter;

        return $this->parseLines();
    }



//     /**
//      * Debugging and visualising parsing by printing information on current row.
//      *
//      * @param string $msg  additional message to print.
//      */
//     private function log($msg)
//     {
//         $lineNumber = $this->lineNumber + $this->linesRemoved;
//         $line = $this->currentLine();
//
//         echo <<<EOD
//
// ***{$lineNumber}: $msg
// {$line}
// ***
//
// EOD;
//     }



    /**
     * Parse each line and look into it to see whats need to be done.
     *
     * @return array with the resulting text and optional additional items.
     */
    public function parseLines()
    {
        $text = [];

        while (($line = $this->nextLine()) !== false) {
            $line = rtrim($line);

            // Skip empty lines
            if ($line == "") {
                $text[] = null;
                continue;
            }

            // Look at start of line to detect valid blocktypes
            $blockTypes = [];
            $marker = $line[0];
            if (isset($this->blockTypes[$marker])) {
                foreach ($this->blockTypes[$marker] as $blockType) {
                    $blockTypes[] = $blockType;
                }
            }

            // Check if line is matching a detected blocktype
            foreach ($blockTypes as $blockType) {
                if ($this->{"block".$blockType}($line)) {
                    continue;
                }
            }
        }

        $text = implode("\n", $this->lines);

        return [
            "text" => $text,
            "frontmatter" => $this->frontmatter,
        ];
    }



    /**
     * Get current line to parse.
     *
     * @return string|boolean containing text for current row or false when
     *                        reached EOF.
     */
    public function currentLine()
    {
        return isset($this->lines[$this->lineNumber - 1])
            ? $this->lines[$this->lineNumber - 1]
            : false;
    }



    /**
     * Get next line to parse and keep track on current line being parsed.
     *
     * @return string|boolean containing text for next row or false when
     *                        reached EOF.
     */
    public function nextLine()
    {
        $this->lineNumber++;
        return $this->currentLine();
    }



    /**
     * Detect and include external file, add it to lines array and parse it.
     *
     * @param string $line to begin parsing.
     *
     * @return boolean|void true when block is found and parsed, else void.
     */
    protected function blockInclude($line)
    {
        if ($this->config["include"]
            && preg_match("/^#include[ \t]([\w.]+)$/", $line, $matches)
        ) {
            $file = $this->config["include_base"]."/".$matches[1];

            if (!is_readable($file)) {
                throw new Exception("Could not find include file: '$file'");
            }

            $include = file_get_contents($file);
            $include = str_replace(array("\r\n", "\r"), "\n", $include);
            $include = explode("\n", $include);
            array_splice(
                $this->lines,
                $this->lineNumber - 1,
                1,
                $include
            );

            $this->lineNumber--;
            return true;
        }
    }



    /**
     * Detect and extract block with YAML frontmatter from the lines array.
     *
     * @param string $line to begin parsing.
     *
     * @return boolean|void true when block is found and parsed, else void.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function blockYamlFrontmatter($line)
    {
        if ($this->config["frontmatter_yaml"]
            && strlen($line) === 3
            && $line[2] === "-"
            && $line[1] === "-"
        ) {
            $startLineNumber = $this->lineNumber;

            // Detect end of block and move it to frontmatter
            while (($line = $this->nextLine()) !== false) {
                $line = rtrim($line);

                // Block ends with --- or ...
                if (strlen($line) === 3
                    && (
                    ($line[2] === "-" && $line[1] === "-" && $line[0] === "-") ||
                    ($line[2] === "." && $line[1] === "." && $line[0] === ".")
                    )
                ) {
                    $linesRemoved = $this->lineNumber + 1 - $startLineNumber;
                    $this->linesRemoved += $linesRemoved;
                    $frontmatter = array_splice(
                        $this->lines,
                        $startLineNumber - 1,
                        $linesRemoved
                    );

                    unset($frontmatter[$linesRemoved - 1]);
                    unset($frontmatter[0]);
                    $this->addYamlFrontmatter($frontmatter);
                    $this->lineNumber = $startLineNumber - 1;

                    return true;
                }
            }

            if ($this->currentLine() === false) {
                throw new Exception("Start of YAML detected at line: $startLineNumber but no end of block detected.");
            }
        }
    }



    /**
     * Extract YAML frontmatter from text and merge into existing frontmatter.
     *
     * @param array $lines the YAML to parsed.
     *
     * @return void.
     */
    protected function addYamlFrontmatter($lines)
    {
        $text = implode("\n", $lines);
        $parsed = $this->parseYaml($text);

        if (!is_array($parsed)) {
            $parsed = [$parsed];
        }

        $this->frontmatter = array_merge($this->frontmatter, $parsed);
    }



    /**
     * Parse YAML front matter from text, use one of several available
     * implementations of a YAML parser.
     *
     * @param string $text the YAML to parsed.
     *
     * @return void.
     *
     * @throws Exception when parsing frontmatter fails of is not installed.
     */
    protected function parseYaml($text)
    {
        if ($this->config["yaml_parser_pecl"]
            && function_exists("yaml_parse")
        ) {
            // PECL php5-yaml extension
            $parsed = yaml_parse($text);

            if ($parsed === false) {
                throw new Exception("Failed parsing YAML frontmatter using PECL.");
            }
            return $parsed;
        }

        if ($this->config["yaml_parser_symfony"]
            && method_exists("Symfony\Component\Yaml\Yaml", "parse")
        ) {
            // symfony/yaml
            $parsed = Yaml::parse($text);
            return $parsed;
        }

        if ($this->config["yaml_parser_spyc"]
            && function_exists("spyc_load")
        ) {
            // mustangostang/spyc
            $parsed = spyc_load($text);
            return $parsed;
        }

        throw new Exception("Could not find support for YAML.");
    }



    /**
     * Detect and extract block with JSON frontmatter from the lines array.
     *
     * @param string $line to begin parsing.
     *
     * @return boolean|void true when block is found and parsed, else void.
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    protected function blockJsonFrontmatter($line)
    {
        if ($this->config["frontmatter_json"]
            && strlen($line) === 3
            && $line[2] === "{"
            && $line[1] === "{"
        ) {
            $startLineNumber = $this->lineNumber;

            // Detect end of block and move it to frontmatter
            while (($line = $this->nextLine()) !== false) {
                $line = rtrim($line);

                // Block ends with }}}
                if (strlen($line) === 3
                    && $line[2] === "}"
                    && $line[1] === "}"
                    && $line[0] === "}"
                ) {
                    $linesRemoved = $this->lineNumber + 1 - $startLineNumber;
                    $this->linesRemoved += $linesRemoved;
                    $frontmatter = array_splice(
                        $this->lines,
                        $startLineNumber - 1,
                        $linesRemoved
                    );

                    unset($frontmatter[$linesRemoved - 1]);
                    unset($frontmatter[0]);
                    $this->addJsonFrontmatter($frontmatter);
                    $this->lineNumber = $startLineNumber - 1;
                    return true;
                }
            }

            if ($this->currentLine() === false) {
                throw new Exception("Start of JSON detected at line: $startLineNumber but no end of block detected.");
            }
        }
    }



    /**
     * Extract JSON frontmatter from text and merge into existing frontmatter.
     *
     * @param array $lines the JSON to parsed.
     *
     * @return void.
     */
    protected function addJsonFrontmatter($lines)
    {
        if (!function_exists("json_decode")) {
            throw new Exception("Missing JSON support, perhaps install JSON module with PHP.");
        }

        $text = implode("\n", $lines);
        $parsed = json_decode($text."\n", true);

        if (is_null($parsed)) {
            throw new Exception("Failed parsing JSON frontmatter.");
        }

        if (!is_array($parsed)) {
            $parsed = [$parsed];
        }

        $this->frontmatter = array_merge($this->frontmatter, $parsed);
    }
}
