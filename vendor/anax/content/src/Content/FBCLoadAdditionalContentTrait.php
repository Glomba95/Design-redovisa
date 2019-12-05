<?php

namespace Anax\Content;

/**
 * File Based Content, code for loading additional content into view through
 * data["meta"].
 */
trait FBCLoadAdditionalContentTrait
{
    /**
     * Load extra info into views based of meta information provided in each
     * view.
     *
     * @param array  &$views     with all views.
     * @param string $route      current route
     * @param string $routeIndex route with appended /index
     *
     * @throws NotFoundException when mapping can not be done.
     *
     * @return void.
     */
    private function loadAdditionalContent(&$views, $route, $routeIndex)
    {
        foreach ($views as $id => $view) {
            $meta = isset($view["data"]["meta"])
                ? $view["data"]["meta"]
                : null;

            if (is_array($meta)) {
                switch ($meta["type"]) {
                    case "article-toc":
                        $content = $views["main"]["data"]["content"];
                        $views[$id]["data"]["articleToc"] = $this->di->get("textfilter")->createToc($content);
                        break;

                    case "breadcrumb":
                        $views[$id]["data"]["breadcrumb"] = $this->createBreadcrumb($route);
                        break;

                    case "next-previous":
                        $baseRoute = dirname($routeIndex);
                        $this->orderToc($baseRoute, $meta);
                        list($next, $previous) = $this->findNextAndPrevious($routeIndex);
                        $views[$id]["data"]["next"] = $next;
                        $views[$id]["data"]["previous"] = $previous;
                        break;

                    case "single": // OBSOLETE
                    case "content":
                        $route = $this->getActiveRoute($meta["route"], $routeIndex);

                        // Load and parse route as view. Load meta view
                        // if any.
                        // Current view details preceds the loaded once.
                        $view = $this->loadAndParseRoute($route);
                        $views[$id] = array_merge_recursive_distinct($view, $views[$id]);
                        break;

                    case "columns":
                        // Each column is an own view set with details
                        // Process as meta view and load additional content
                        $template = isset($meta["template"])
                            ? $meta["template"]
                            : null;
                        $columns = $meta["columns"];
                        foreach ($columns as $key => $view) {
                            $views2 = [ "main" => $view ];
                            $this->loadAdditionalContent($views2, $route, $routeIndex);
                            $columns[$key] = $views2["main"];
                            
                            if ($template) {
                                $columns[$key]["template"] = $template;
                            }
                        }
                        $views[$id]["data"]["columns"] = $columns;
                        break;

                    case "toc-sort":
                        $baseRoute = dirname($routeIndex);
                        $this->orderToc($baseRoute, $meta);
                        break;

                    case "toc":
                        $baseRoute = dirname($routeIndex);

                        // Include support for ordering
                        if (isset($meta["orderby"])
                            || isset($meta["orderorder"])) {
                            $this->orderToc($baseRoute, $meta);
                        }

                        // Same as toc-route
                        $toc = $this->meta[$baseRoute]["__toc__"];
                        $this->limitToc($toc, $meta);
                        $views[$id]["data"]["toc"] = $toc;
                        $views[$id]["data"]["meta"] = $meta;
                        break;

                    case "toc-route":
                        // Get the toc for a specific route
                        $route = $this->getActiveRoute($meta["route"], $routeIndex);
                        $routeIndex2 = $this->mapRoute2IndexKey($route);
                        $baseRoute = dirname($routeIndex2);

                        // Include support for ordering
                        if (isset($meta["orderby"])
                            || isset($meta["orderorder"])) {
                            $this->orderToc($baseRoute, $meta);
                        }

                        // Same as toc
                        $toc = $this->meta[$baseRoute]["__toc__"];
                        $this->limitToc($toc, $meta, $baseRoute);
                        $views[$id]["data"]["toc"] = $toc;
                        $views[$id]["data"]["meta"] = $meta;
                        break;

                    case "book-toc":
                        $toc = $this->meta[$baseRoute]["__toc__"];
                        $views[$id]["data"]["toc"] = $toc;
                        break;

                    case "author":
                        if (isset($views["main"]["data"]["author"])) {
                            $views[$id]["data"]["author"] = $this->loadAuthorDetails($views["main"]["data"]["author"]);
                        }
                        break;

                    case "copy":
                        $viewToCopy = $views[$id]["data"]["meta"]["view"];
                        $views[$id]["data"] = array_merge_recursive_distinct(
                            $views[$viewToCopy]["data"],
                            $views[$id]["data"]
                        );
                        break;

                    default:
                        $msg = t("Unsupported data/meta/type '!TYPE' for additional content.", [
                            "!TYPE" => $meta["type"]
                        ]);
                        throw new Exception($msg);
                }
            }
        }
    }



    /**
     * Find next and previous links of current content.
     *
     * @param string $routeIndex target route to find next and previous for.
     *
     * @return array with next and previous if found.
     */
    private function findNextAndPrevious($routeIndex)
    {
        $key = dirname($routeIndex);
        if (!isset($this->meta[$key]["__toc__"])) {
            return [null, null];
        }

        $toc = $this->meta[$key]["__toc__"];
        if (!isset($toc[$routeIndex])) {
            return [null, null];
        }

        $index2Key = array_keys($toc);
        $keys = array_flip($index2Key);
        $values = array_values($toc);
        $count = count($keys);

        $current = $keys[$routeIndex];
        $previous = null;
        for ($i = $current - 1; $i >= 0; $i--) {
            $isSectionHeader = $values[$i]["sectionHeader"];
            $isLinkable = $values[$i]["linkable"]; // ?? null;
            $isInternal = $values[$i]["internal"];
            if (($isSectionHeader && !$isLinkable) || $isInternal) {
                continue;
            }
            $previous = $values[$i];
            $previous["route"] = $index2Key[$i];
            break;
        }
        
        $next = null;
        for ($i = $current + 1; $i < $count; $i++) {
            $isSectionHeader = $values[$i]["sectionHeader"];
            $isLinkable = $values[$i]["linkable"]; // ?? null;
            $isInternal = $values[$i]["internal"];
            if (($isSectionHeader && !$isLinkable) || $isInternal) {
                continue;
            }
            $next = $values[$i];
            $next["route"] = $index2Key[$i];
            break;
        }

        return [$next, $previous];
    }



    /**
     * Order toc items.
     *
     * @param string $baseRoute route to use to find __toc__.
     * @param string $meta on how to order toc.
     *
     * @return void.
     */
    private function orderToc($baseRoute, $meta)
    {
        $defaults = [
            "orderby" => "section",
            "orderorder" => "asc",
        ];
        $options = array_merge($defaults, $meta);
        $orderby = $options["orderby"];
        $order   = $options["orderorder"];
        $toc = $this->meta[$baseRoute]["__toc__"];

        uksort($toc, function ($a, $b) use ($toc, $orderby, $order) {
                $a = $toc[$a][$orderby];
                $b = $toc[$b][$orderby];

                $asc = $order == "asc" ? 1 : -1;
                
            if ($a == $b) {
                return 0;
            } elseif ($a > $b) {
                return $asc;
            }
                return -$asc;
        });

        $this->meta[$baseRoute]["__toc__"] = $toc;
    }


    /**
     * Limit and paginate toc items.
     *
     * @param string &$toc      array with current toc.
     * @param string &$meta     on how to order and limit toc.
     * @param string $baseRoute prepend to next & previous urls.
     *
     * @return void.
     */
    private function limitToc(&$toc, &$meta, $baseRoute = null)
    {
        $defaults = [
            "items" => 7,
            "offset" => 0,
        ];
        $options = array_merge($defaults, $meta);

        // Check if pagination is currently used
        if ($this->currentPage) {
            $options["offset"] = ($this->currentPage - 1) * $options["items"];
        }

        $meta["totalItems"] = count($toc);
        $meta["currentPage"] = (int) floor($options["offset"] / $options["items"]) + 1;
        $meta["totalPages"] = (int) floor($meta["totalItems"] / $options["items"] + 1);

        // Next and previous page
        $pagination = $this->config["pagination"];
        $meta["nextPageUrl"] = null;
        $meta["previousPageUrl"] = null;
        $baseRoute = isset($baseRoute)
            ? $baseRoute
            : $this->baseRoute;

        if ($meta["currentPage"] > 1 && $meta["totalPages"] > 1) {
            $previousPage = $meta["currentPage"] - 1;
            $previous = "";
            if ($previousPage != 1) {
                $previous = "$pagination/$previousPage";
            }
            $meta["previousPageUrl"] = "$baseRoute/$previous";
        }

        if ($meta["currentPage"] < $meta["totalPages"]) {
            $nextPage = $meta["currentPage"] + 1;
            $meta["nextPageUrl"] = "$baseRoute/$pagination/$nextPage";
        }


        // Only use slice of toc
        $startSlice = ($meta["currentPage"] - 1) * $options["items"];
        $toc = array_slice($toc, $startSlice, $options["items"]);
        $meta["displayedItems"] = count($toc);
    }
}
