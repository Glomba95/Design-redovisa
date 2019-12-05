<?php

namespace Anax\Route;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Route\Exception\ConfigurationException;

/**
 * Matching a incoming path to see it it matches a route.
 */
class RouteMatcher
{
    /**
     * @var null|array   $arguments     arguments for the callback, extracted
     *                                  from path
     * @var string       $methodMatched the matched method.
     * @var string       $pathMatched   the matched path.
     */
    public $arguments = [];
    public $methodMatched;
    public $pathMatched;



    /**
     * Check if part of route is a argument and optionally match type
     * as a requirement {argument:type}.
     *
     * @param string $rulePart   the rule part to check.
     * @param string $queryPart  the query part to check.
     * @param array  &$args      add argument to args array if matched
     *
     * @return boolean
     */
    private function checkPartAsArgument($rulePart, $queryPart, &$args)
    {
        if (substr($rulePart, -1) == "}"
            && !is_null($queryPart)
        ) {
            $part = substr($rulePart, 1, -1);
            $pos = strpos($part, ":");
            $type = null;
            if ($pos !== false) {
                $type = substr($part, $pos + 1);
                if (! $this->checkPartMatchingType($queryPart, $type)) {
                    return false;
                }
            }
            $args[] = $this->typeConvertArgument($queryPart, $type);
            return true;
        }
        return false;
    }



    /**
     * Check if value is matching a certain type of values.
     *
     * @param string $value   the value to check.
     * @param array  $type    the expected type to check against.
     *
     * @return boolean
     */
    private function checkPartMatchingType($value, $type)
    {
        switch ($type) {
            case "digit":
                return ctype_digit($value);
                break;

            case "hex":
                return ctype_xdigit($value);
                break;

            case "alpha":
                return ctype_alpha($value);
                break;

            case "alphanum":
                return ctype_alnum($value);
                break;

            default:
                return false;
        }
    }



    /**
     * Check if value is matching a certain type and do type
     * conversion accordingly.
     *
     * @param string $value   the value to check.
     * @param array  $type    the expected type to check against.
     *
     * @return boolean
     */
    private function typeConvertArgument($value, $type)
    {
        switch ($type) {
            case "digit":
                return (int) $value;
                break;

            default:
                return $value;
        }
    }



    /**
     * Match part of rule and query.
     *
     * @param string $rulePart   the rule part to check.
     * @param string $queryPart  the query part to check.
     * @param array  &$args      add argument to args array if matched
     *
     * @return boolean
     */
    private function matchPart($rulePart, $queryPart, &$args)
    {
        $match = false;
        $first = isset($rulePart[0]) ? $rulePart[0] : '';
        switch ($first) {
            case '*':
                $match = true;
                break;

            case '{':
                $match = $this->checkPartAsArgument($rulePart, $queryPart, $args);
                break;

            default:
                $match = ($rulePart == $queryPart);
                break;
        }
        return $match;
    }



    /**
     * Check if the request method matches.
     *
     * @param string $method    as request method.
     * @param string $supported as request methods that are valid.
     *
     * @return boolean true if request method matches
     */
    private function matchRequestMethod(
        string $method = null,
        array $supported = null
    ) {
        if ($supported && !in_array($method, $supported)) {
            return false;
        }

        return true;
    }



    /**
     * Check if the route matches a query and request method.
     *
     * @param string $mount           of the current route being matched.
     * @param string $relativePath    of the current route being matched.
     * @param string $absolutePath    of the current route being matched.
     * @param string $query           to match against
     * @param array  $methodSupported as supported request method
     * @param string $method          as request method
     *
     * @return boolean true if query matches the route
     */
    public function match(
        string $mount = null,
        string $relativePath = null,
        string $absolutePath = null,
        string $query,
        array $methodSupported = null,
        string $method = null
    ) {
        $this->arguments = [];
        $this->methodMatched = null;
        $this->pathMatched = null;

        if (!$this->matchRequestMethod($method, $methodSupported)) {
            return false;
        }

        // Is a null path  - mounted on empty, or mount path matches
        // initial query.
        if (is_null($relativePath)
            && (empty($mount) || strncmp($query, $mount, strlen($mount)) == 0)
        ) {
            $this->methodMatched = $method;
            $this->pathMatched = $query;
            return true;
        }

        // Check all parts to see if they matches
        $ruleParts  = explode('/', $absolutePath);
        $queryParts = explode('/', $query);
        $ruleCount = max(count($ruleParts), count($queryParts));
        $args = [];

        for ($i = 0; $i < $ruleCount; $i++) {
            $rulePart  = isset($ruleParts[$i])  ? $ruleParts[$i]  : null;
            $queryPart = isset($queryParts[$i]) ? $queryParts[$i] : null;

            if ($rulePart === "**") {
                break;
            }

            if (!$this->matchPart($rulePart, $queryPart, $args)) {
                return false;
            }
        }

        $this->arguments = $args;
        $this->methodMatched = $method;
        $this->pathMatched = $query;
        return true;
    }
}
