<?php

namespace Anax\View;

/**
 * Define helper functions to include before processing the view template.
 * The functions here are exposed to the view and can be used in the view.
 */

/**
 * Shortcut to create an url for a static asset.
 *
 * @param string $url url to use when creating the url.
 *
 * @return string as resulting url.
 */
function asset($url = "")
{
    global $di;
    return $di->get("url")->asset($url);
}



/**
 * Shortcut to create an url for routing in the framework.
 *
 * @param null|string $url url to use when creating the url.
 *
 * @return string as resulting url.
 */
function url($url = "")
{
    global $di;
    return $di->get("url")->create($url);
}



/**
 * Render a view with an optional data set of variables.
 *
 * @param string $template the template file, or array
 * @param array  $data     variables to make available to the
 *                         view, default is empty
 *
 * @return void
 */
function renderView($template, $data = [])
{
    global $di;
    $view = new View();
    $template = $di->get("view")->getTemplateFile($template);
    $view->set($template, $data);
    $view->render($di);
}



/**
 * Check if the region in the view container has views to render.
 *
 * @param string $region to check
 *
 * @return boolean true or false
 */
function regionHasContent($region)
{
    global $di;
    return $di->get("view")->hasContent($region);
}



/**
 * Render views, from the view container, in the region.
 *
 * @param string $region to render in
 *
 * @return boolean true or false
 */
function renderRegion($region)
{
    global $di;
    return $di->get("view")->render($region);
}



/**
 * Create a class attribute from a string or array.
 *
 * @param string|array $args variable amount of classlists.
 *
 * @return string as complete class attribute
 */
function classList(...$args)
{
    $classes = [];

    foreach ($args as $arg) {
        if (empty($arg)) {
            continue;
        } elseif (is_string($arg)) {
            $arg = explode(" ", $arg);
        }
        $classes = array_merge($classes, $arg);
    }

    return "class=\"" . implode(" ", $classes) . "\"";
}



/**
 * Get current url, without querystring.
 *
 * @return string as resulting url.
 */
function currentUrl()
{
    global $di;
    return $di->get("request")->getCurrentUrl(false);
}



/**
 * Get current route.
 *
 * @return string as resulting route.
 */
function currentRoute()
{
    global $di;
    return $di->get("request")->getRoute();
}



/**
 * Redirect to another url.
 *
 * @param string $url to be redirected to.
 *
 * @return void.
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 *
 */
function redirect(string $url) : void
{
    global $di;
    $di->get("response")->redirect($url)->send();
    exit;
}



/**
 * Escape HTML entities.
 *
 * @param string $string to be escaped.
 *
 * @return string as resulting route.
 */
function e($string) : string
{
    return htmlentities($string);
}



/**
 * Show variables/functions that are currently defined and can
 * be used within the view. Call the function with get_defined_vars()
 * as the parameter.
 *
 * @param array $variables should be the returned value from
 *                         get_defined_vars()
 * @param array $functions should be the returned value from
 *                         get_defined_functions()
 *
 * @return string showing variables and functions.
 */
function showEnvironment($variables, $functions)
{
    $all = array_keys($variables);
    sort($all);
    $res = "<pre>\n"
        . "VIEW DEVELOPMENT INFORMATION\n"
        . "----------------------------\n"
        . "Variables available:\n"
        . " (var_dump each for more information):\n";
    foreach ($all as $var) {
        $variable = $variables[$var];
        $res .= "* $var (" . gettype($variable) . ")";
        if (is_integer($variable) || is_double($variable)) {
            $res .= ": $variable";
        } elseif (is_string($variable)) {
            $res .= ": \"$variable\"";
        } elseif (is_bool($variable)) {
            $res .= ": " . ( $variable ? "true" : "false" );
        }
        $res .= "\n";
    }

    $res .= "\nView helper functions available:\n (see " . __FILE__ . ")\n";
    $namespace = strtolower(__NAMESPACE__);
    $matches = array_filter(
        $functions["user"],
        function ($function) use ($namespace) {
            return substr($function, 0, strlen($namespace)) === $namespace;
        }
    );
    sort($matches);
    $res .= "* " . implode(",\n* ", $matches);
    $res .= "</pre>";

    return $res;
}



/**
 * Extract the publish or update date for the article.
 *
 * @param array $dates a collection of possible date values.
 *
 * @return array with values for showing the date.
 */
function getPublishedDate($dates)
{
    $defaults = [
        "revision" => [],
        "published" => null,
        "updated" => null,
        "created" => null,
    ];
    $dates = array_merge($defaults, $dates);

    if ($dates["revision"]) {
        return [t("Latest revision"), key($dates["revision"])];
    } elseif ($dates["published"]) {
        return [t("Published"), $dates["published"]];
    } elseif ($dates["updated"]) {
        return [t("Updated"), $dates["updated"]];
    } elseif ($dates["created"]) {
        return [t("Created"), $dates["created"]];
    }

    return [t("Missing pubdate."), null];
}



/**
 * Load content from a route and return details to view.
 *
 * @param string $route to load content from.
 *
 * @return array with values to extract in view.
 */
function getContentForRoute($route)
{
    global $di;
    $content = $di->get("content")->contentForInternalRoute($route);
    return $content->views["main"]["data"];
}



/**
 * Wrap a HTML element with start and end.
 *
 * @param string  $text  with content
 * @param string  $tag   HTML tag to search for
 * @param string  $start wrap start part
 * @param string  $end   wrap end part
 * @param number  $count hits to search for
 *
 * @return array with values to extract in view.
 */
function wrapElementWithStartEnd($text, $tag, $start, $end, $count)
{
    global $di;
    return $di->get("textfilter")->wrapElementWithStartEnd($text, $tag, $start, $end, $count);
}



/**
 * Wrap content of a HTML element with start and end.
 *
 * @param string  $text  with content
 * @param string  $tag   HTML tag to search for
 * @param string  $start wrap start part
 * @param string  $end   wrap end part
 * @param number  $count hits to search for
 *
 * @return array with values to extract in view.
 */
function wrapElementContentWithStartEnd($text, $tag, $start, $end, $count)
{
    global $di;
    return $di->get("textfilter")->wrapElementContentWithStartEnd($text, $tag, $start, $end, $count);
}
