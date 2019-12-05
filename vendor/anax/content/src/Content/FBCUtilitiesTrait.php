<?php

namespace Anax\Content;

/**
 * File Based Content, code for loading additional content into view through
 * data["meta"].
 */
trait FBCUtilitiesTrait
{
    /**
     * Support relative routes.
     *
     * @param string $route      to load.
     * @param string $routeIndex to use.
     *
     * @return string with active route.
     */
    private function getActiveRoute($route, $routeIndex)
    {
        if (substr_compare($route, "./", 0, 2) === 0) {
            $route = dirname($routeIndex) . "/" . substr($route, 2);
        }

        return $route;
    }



    /**
     * Process content phase 2 and merge with new frontmatter into
     * view structure.
     *
     * @param string &$views array to load view info into.
     * @param string  $route to load meta from.
     *
     * @return void
     */
    private function processContentPhaseTwo(&$filtered)
    {
        $filter     = $this->config["textfilter"];
        $textFilter = $this->di->get("textfilter");

        // Get new filtered content (and updated frontmatter)
        $new = $textFilter->parse($filtered->text, $filter);
        $filtered->text = $new->text;
        $filtered->frontmatter = array_merge_recursive_distinct(
            $filtered->frontmatter,
            $new->frontmatter
        );

        // Update all anchor urls to use baseurl, needs info about baseurl
        // from merged frontmatter
        $baseurl = isset($filtered->frontmatter["baseurl"])
           ? $filtered->frontmatter["baseurl"]
           : null;
        $this->addBaseurl2AnchorUrls($filtered, $baseurl);
        $this->addBaseurl2ImageSource($filtered, $baseurl);

        // Add excerpt and hasMore, if available
        $textFilter->addExcerpt($filtered);
    }




    /**
     * Load view details for additional route, merged with meta if any.
     *
     * @param string $route to load.
     *
     * @return array with view data details.
     */
    private function loadAndParseRoute($route)
    {
         // Get meta into view structure
         $meta = $this->getMetaForRoute($route);
         unset($meta["__toc__"]);
         unset($meta["views"]);

        // Get filtered content from route
        list($routeIndex, , $filtered) =
            $this->mapRoute2Content($route);

        // Merge frontmatter with meta
        // then merge frontmatter base into views main
        $filtered->frontmatter = array_merge_recursive_distinct(
            $meta,
            $filtered->frontmatter
        );

        // Do phase 2 processing to get new filtered content
        // (and updated frontmatter)
        $this->processContentPhaseTwo($filtered);

        // Create complete frontmatter, inluding content
        $filtered->frontmatter["data"]["content"] = isset($filtered->text)
            ? $filtered->text
            : null;

        // Load additional content for view, based on data-meta
        $view = ["main" => $filtered->frontmatter];
        $this->loadAdditionalContent($view, $route, $routeIndex);

        return $view["main"];
    }



    /**
     * Load view data for additional route, merged with meta if any.
     *
     * @param string $route to load.
     *
     * @return array with view data details.
     */
    private function getDataForAdditionalRoute($route)
    {
         $filter     = $this->config["textfilter"];
         $textFilter = $this->di->get("textfilter");

        // Get filtered content from route
        list($routeIndex, , $filtered) =
            $this->mapRoute2Content($route);

        // Get meta, remove unneeded details
        $meta = $this->getMetaForRoute($route);
        unset($meta["__toc__"]);
        unset($meta["views"]);

        // Do phase 2 processing to get new filtered content
        // (and updated frontmatter)
        $new = $textFilter->parse($filtered->text, $filter);
        $new->frontmatter = array_merge_recursive_distinct($filtered->frontmatter, $new->frontmatter);

        // Creates urls based on baseurl
        $baseurl = isset($new->frontmatter["data"]["baseurl"])
            ? isset($new->frontmatter["data"]["baseurl"])
            : null;
        $this->addBaseurl2AnchorUrls($new, $baseurl);
        $this->addBaseurl2ImageSource($new, $baseurl);

        // Create complete frontmatter, inluding content
        $frontmatter = $new->frontmatter;
        $frontmatter["data"]["content"] = $new->text;

        // Load additional content for view, based on data-meta
        $view = ["main" => $frontmatter];
        $this->loadAdditionalContent($view, $route, $routeIndex);

        return $view["main"];
    }



    /**
     * Parse text, find and update all a href to use baseurl.
     *
     * @param object &$filtered with text and excerpt to process.
     * @param string $baseurl   add as baseurl for all relative urls.
     *
     * @return void.
     */
    private function addBaseurl2AnchorUrls(&$filtered, $baseurl)
    {
        $textf   = $this->di->get("textfilter");
        $url     = $this->di->get("url");
        $request = $this->di->get("request");
        $part = $request->getRoute();

        // Use callback to url->create() instead of string concat
        $callback = function ($route) use ($url, $baseurl, $part) {
            if (!empty($route) && $route[0] == "!") {
                return $url->asset(substr($route, 1), $baseurl);
            }

            if (isset($route[0])
                && isset($route[1])
                && $route[0] === "."
                && $route[1] === "/"
            ) {
                return $url->create(
                    substr($route, 2),
                    $baseurl . $part
                );
            }

            return $url->create($route, $baseurl);
        };

        $filtered->text =
            $textf->addBaseurlToRelativeLinks($filtered->text, $baseurl, $callback);
    }



    /**
     * Parse text, find and update all image source to use baseurl.
     *
     * @param object &$filtered with text and excerpt to process.
     * @param string $baseurl   add as baseurl for all relative urls.
     *
     * @return void.
     */
    private function addBaseurl2ImageSource(&$filtered, $baseurl)
    {
        $textf  = $this->di->get("textfilter");
        $url    = $this->di->get("url");

        // Use callback to url->create() instead of string concat
        $callback = function ($route) use ($url, $baseurl) {
            return $url->asset($route, $baseurl);
        };

        $filtered->text =
            $textf->addBaseurlToImageSource($filtered->text, $baseurl, $callback);
    }



    /**
     * Get published date.
     *
     * @param array $frontmatter with details on dates.
     *
     * @return integer as time for publish time.
     */
    private function getPublishTime($frontmatter)
    {
        //list(, $date) = $this->di->get("view")->getPublishedDate($frontmatter);
        list(, $date) = \Anax\View\getPublishedDate($frontmatter);
        return strtotime($date);
    }
}
