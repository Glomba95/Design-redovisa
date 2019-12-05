<?php

namespace Anax\Content;

/**
 * File Based Content, code for creating breadcrumb.
 */
trait FBCBreadcrumbTrait
{
    /**
     * Create a breadcrumb, append slash / to all dirs.
     *
     * @param string $route      current route.
     *
     * @return array with values for the breadcrumb.
     */
    public function createBreadcrumb($route)
    {
        $breadcrumbs = [];

        while ($route !== "./" && $route !== "/") {
            $routeIndex = $this->mapRoute2IndexKey($route);
            $item["url"] = $route;
            $item["text"] = $this->getBreadcrumbTitle($this->index[$routeIndex]["file"]);
            $breadcrumbs[] = $item;
            $route = dirname($route) . "/";
        }

        krsort($breadcrumbs);
        return $breadcrumbs;
    }



    /**
     * Get the title of a document to use for breadcrumb.
     *
     * @param string $file to get title from.
     *
     * @return string as the breadcrumb title.
     */
    private function getBreadcrumbTitle($file)
    {
        $frontmatter = $this->getFrontmatter($file);
        $title = $frontmatter["title"];
        if (isset($frontmatter["titleBreadcrumb"])) {
            $title = $frontmatter["titleBreadcrumb"];
        }

        return $title;
    }
}
