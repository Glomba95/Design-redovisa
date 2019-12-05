<?php

namespace Anax\Content;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Route\Exception\NotFoundException;

/**
 * Pages based on file content.
 */
class FileBasedContent implements ContainerInjectableInterface
{
    use ContainerInjectableTrait,
        FBCBreadcrumbTrait,
        FBCLoadAdditionalContentTrait,
        FBCUtilitiesTrait;



    /**
     * All routes.
     */
    private $index = null;

    /**
     * All authors.
     */
    private $author = null;

    /**
     * All categories.
     */
    private $category = null;

    /**
     * All routes having meta.
     */
    private $meta = null;

    /**
     * This is the base route.
     */
    private $baseRoute = null;

    /**
     * This is the extendede meta route, if any.
     */
    private $metaRoute = null;

    /**
     * This is the current page, to supply pagination, if used.
     */
    private $currentPage = null;

    /**
     * Use cache or recreate each time.
     */
    private $ignoreCache = false;
    
    /**
     * File name pattern, all files must match this pattern and the first
     * numbered part is optional, the second part becomes the route.
     */
    private $filenamePattern = "#^(\d*)_*([^\.]+)\.md$#";

    /**
     * Internal routes that is marked as internal content routes and not
     * exposed as public routes.
     */
    private $internalRouteDirPattern = [
        "#block/#",
    ];

    private $internalRouteFilePattern = [
        "#^block[_-]{1}#",
        "#^_#",
    ];

    /**
     * Routes that should be used in toc.
     */
    private $allowedInTocPattern = "([\d]+_(\w)+)";



    /**
     * Set default values from configuration.
     *
     * @param array $config the configuration to use.
     *
     * @return void
     */
    public function configure(array $config) : void
    {
        $this->config = $config;
        $this->setDefaultsFromConfiguration();
    }



    /**
     * Set default values from configuration.
     *
     * @return this.
     */
    private function setDefaultsFromConfiguration()
    {
        $this->ignoreCache = isset($this->config["ignoreCache"])
            ? $this->config["ignoreCache"]
            : $this->ignoreCache;

        return $this;
    }



    /**
     * Should the cache be used or ignored.
     *
     * @param boolean $use true to use the cache or false to ignore the cache
     *
     * @return this.
     */
    public function useCache($use)
    {
        $this->ignoreCache = !$use;

        return $this;
    }



    /**
     * Create the index of all content into an array.
     *
     * @param string $type of index to load.
     *
     * @return void.
     */
    private function load($type)
    {
        $index = $this->$type;
        if ($index) {
            return;
        }

        $cache = $this->di->get("cache");
        $key = $cache->createKey(__CLASS__, $type);
        $index = $cache->get($key);

        if (is_null($index) || $this->ignoreCache) {
            $createMethod = "create$type";
            $index = $this->$createMethod();
            $cache->set($key, $index);
        }

        $this->$type = $index;
    }




    // = Create and manage index ==================================

    /**
     * Generate an index from the directory structure.
     *
     * @return array as index for all content files.
     */
    private function createIndex()
    {
        $basepath   = $this->config["basePath"];
        $pattern    = $this->config["pattern"];
        $path       = "$basepath/$pattern";

        $index = [];
        foreach (glob_recursive($path) as $file) {
            $filepath = substr($file, strlen($basepath) + 1);

            // Find content files
            $matches = [];
            preg_match($this->filenamePattern, basename($filepath), $matches);
            $dirpart = dirname($filepath) . "/";
            if ($dirpart === "./") {
                $dirpart = null;
            }
            $key = $dirpart . $matches[2];
            
            // Create level depending on the file id
            // TODO ciamge doc, can be replaced by __toc__ in meta?
            $id = (int) $matches[1];
            $level = 2;
            if ($id % 100 === 0) {
                $level = 0;
            } elseif ($id % 10 === 0) {
                $level = 1;
            }

            $index[$key] = [
                "file"     => $filepath,
                "section"  => $matches[1],
                "level"    => $level,  // TODO ?
                "internal" => $this->isInternalRoute($filepath),
                "tocable"  => $this->allowInToc($filepath),
            ];
        }

        return $index;
    }



    /**
     * Check if a filename is to be marked as an internal route..
     *
     * @param string $filepath as the basepath (routepart) to the file.
     *
     * @return boolean true if the route content is internal, else false
     */
    private function isInternalRoute($filepath)
    {
        foreach ($this->internalRouteDirPattern as $pattern) {
            if (preg_match($pattern, $filepath)) {
                return true;
            }
        }

        $filename = basename($filepath);
        foreach ($this->internalRouteFilePattern as $pattern) {
            if (preg_match($pattern, $filename)) {
                return true;
            }
        }

        return false;
    }



    /**
     * Check if filepath should be used as part of toc.
     *
     * @param string $filepath as the basepath (routepart) to the file.
     *
     * @return boolean true if the route content shoul dbe in toc, else false
     */
    private function allowInToc($filepath)
    {
        return (boolean) preg_match($this->allowedInTocPattern, $filepath);
    }



    // = Create and manage meta ==================================

    /**
     * Generate an index for meta files.
     *
     * @return array as meta index.
     */
    private function createMeta()
    {
        $basepath = $this->config["basePath"];
        $filter   = $this->config["textfilter-frontmatter"];
        $pattern  = $this->config["meta"];
        $path     = "$basepath/$pattern";
        $textfilter = $this->di->get("textfilter");

        $index = [];
        foreach (glob_recursive($path) as $file) {
            // The key entry to index
            $key = dirname(substr($file, strlen($basepath) + 1));

            // Get info from base document
            $src = file_get_contents($file);
            $filtered = $textfilter->parse($src, $filter);
            $index[$key] = $filtered->frontmatter;

            // Add Toc to the data array
            $index[$key]["__toc__"] = $this->createBaseRouteToc($key);
        }

        // Add author details
        $this->meta = $index;
        $this->createAuthor();
        $this->createCategory();

        return $this->meta;
    }



    /**
     * Get a reference to meta data for specific route.
     *
     * @param string $route current route used to access page.
     *
     * @return array as table of content.
     */
    private function getMetaForRoute($route)
    {
        $base = dirname($route);
        return isset($this->meta[$base])
            ? $this->meta[$base]
            : [];
    }



    /**
     * Create a table of content for routes at particular level.
     *
     * @param string $route base route to use.
     *
     * @return array as the toc.
     */
    private function createBaseRouteToc($route)
    {
        $toc = [];
        $len = strlen($route);

        foreach ($this->index as $key => $value) {
            if (substr($key, 0, $len + 1) === "$route/") {
                if ($value["internal"] === false
                    && $value["tocable"] === true) {
                    $toc[$key] = $value;
                    
                    $frontm = $this->getFrontmatter($value["file"]);
                    $toc[$key]["title"] = $frontm["title"];
                    $toc[$key]["publishTime"] = $this->getPublishTime($frontm);
                    $toc[$key]["sectionHeader"] = isset($frontm["sectionHeader"])
                        ? $frontm["sectionHeader"]
                        : null;
                    $toc[$key]["linkable"] = isset($frontm["linkable"])
                        ? $frontm["linkable"]
                        : null;
                }
            }
        };

        return $toc;
    }



    // = Deal with authors ====================================
    
    /**
     * Generate a lookup index for authors that maps into the meta entry
     * for the author.
     *
     * @return void.
     */
    private function createAuthor()
    {
        $pattern = $this->config["author"];

        $index = [];
        $matches = [];
        foreach ($this->meta as $key => $entry) {
            if (preg_match($pattern, $key, $matches)) {
                $acronym = $matches[1];
                $index[$acronym] = $key;
                $this->meta[$key]["acronym"] = $acronym;
                $this->meta[$key]["url"] = $key;
                unset($this->meta[$key]["__toc__"]);

                // Get content for byline
                $route = "$key/byline";
                $data = $this->getDataForAdditionalRoute($route);
                $byline = isset($data["data"]["content"]) ? $data["data"]["content"] : null;
                $this->meta[$key]["byline"] = $byline;
            }
        }

        return $index;
    }



    /**
     * Load details for the author.
     *
     * @param array|string $author with details on the author(s).
     *
     * @return array with more details on the authors(s).
     */
    private function loadAuthorDetails($author)
    {
        if (is_array($author) && is_array(array_values($author)[0])) {
            return $author;
        }

        if (!is_array($author)) {
            $tmp = $author;
            $author = [];
            $author[] = $tmp;
        }

        $authors = [];
        foreach ($author as $acronym) {
            if (isset($this->author[$acronym])) {
                $key = $this->author[$acronym];
                $authors[$acronym] = $this->meta[$key];
            } else {
                $authors[$acronym]["acronym"] = $acronym;
            }
        }

        return $authors;
    }



    // = Deal with categories ====================================
    
    /**
     * Generate a lookup index for categories that maps into the meta entry
     * for the category.
     *
     * @return void.
     */
    private function createCategory()
    {
        $pattern = $this->config["category"];

        $index = [];
        $matches = [];
        foreach ($this->meta as $key => $entry) {
            if (preg_match($pattern, $key, $matches)) {
                $catKey = $matches[1];
                $index[$catKey] = $key;
                $this->meta[$key]["key"] = $catKey;
                $this->meta[$key]["url"] = $key;
                unset($this->meta[$key]["__toc__"]);
            }
        }

        return $index;
    }



    /**
     * Find next and previous links of current content.
     *
     * @param array|string $author with details on the category(s).
     *
     * @return array with more details on the category(s).
     */
    private function loadCategoryDetails($category)
    {
        if (is_array($category) && is_array(array_values($category)[0])) {
            return $category;
        }

        if (!is_array($category)) {
            $tmp = $category;
            $category = [];
            $category[] = $tmp;
        }

        $categorys = [];
        foreach ($category as $catKey) {
            if (isset($this->category[$catKey])) {
                $key = $this->category[$catKey];
                $categorys[$catKey] = $this->meta[$key];
            } else {
                $categorys[$catKey]["key"] = $catKey;
            }
        }

        return $categorys;
    }




    // == Used by meta and breadcrumb (to get title) ===========================
    // TODO REFACTOR THIS?
    // Support getting only frontmatter.
    // Merge with function that retrieves whole filtered since getting
    // frontmatter will involve full parsing of document.
    // Title is retrieved from the HTML code.
    // Also do cacheing of each retrieved and parsed document
    // in this cycle, to gather code that loads and parses a individual
    // document.
    
    /**
     * Get the frontmatter of a document.
     *
     * @param string $file to get frontmatter from.
     *
     * @return array as frontmatter.
     */
    private function getFrontmatter($file)
    {
        $basepath = $this->config["basePath"];
        $filter1  = $this->config["textfilter-frontmatter"];
        $filter2  = $this->config["textfilter-title"];
        $filter = array_merge($filter1, $filter2);
        
        $path = $basepath . "/" . $file;
        $src = file_get_contents($path);
        $filtered = $this->di->get("textfilter")->parse($src, $filter);
        return $filtered->frontmatter;
    }



    // == Look up route in index ===================================
    
    /**
     * Check if currrent route is a supported meta route.
     *
     * @param string $route current route used to access page.
     *
     * @return string as route.
     */
    private function checkForMetaRoute($route)
    {
        $this->baseRoute = $route;
        $this->metaRoute = null;

        // If route exits in index, use it
        if ($this->mapRoute2IndexKey($route)) {
            return $route;
        }

        // Check for pagination
        $pagination = $this->config["pagination"];
        $matches = [];
        $pattern = "/(.*?)\/($pagination)\/(\d+)$/";
        if (preg_match($pattern, $route, $matches)) {
            $this->baseRoute = $matches[1];
            $this->metaRoute = $route;
            $this->currentPage = $matches[3];
        }

        return $this->baseRoute;
    }



    /**
     * Map the route to the correct key in the index.
     *
     * @param string $route current route used to access page.
     *
     * @return string as key or false if no match.
     */
    private function mapRoute2IndexKey($route)
    {
        $route = rtrim($route, "/");

        if (key_exists($route, $this->index)) {
            return $route;
        } elseif (empty($route) && key_exists("index", $this->index)) {
            return "index";
        } elseif (key_exists($route . "/index", $this->index)) {
            return "$route/index";
        }

        return false;
    }



    /**
     * Map the route to the correct entry in the index.
     *
     * @param string $route current route used to access page.
     *
     * @return array as the matched route.
     */
    private function mapRoute2Index($route)
    {
        $routeIndex = $this->mapRoute2IndexKey($route);

        if ($routeIndex) {
            return [$routeIndex, $this->index[$routeIndex]];
        }

        $msg = t("The route '!ROUTE' does not exists in the index.", [
            "!ROUTE" => $route
        ]);
        throw new NotFoundException($msg);
    }



    // = Get view data by merging from meta and current frontmatter =========
    
    /**
     * Get view by mergin information from meta and frontmatter.
     *
     * @param string $route       current route used to access page.
     * @param array  $frontmatter for the content.
     * @param string $key         for the view to retrive.
     *
     * @return array with data to add as view.
     */
    private function getView($route, $frontmatter, $key)
    {
        $view = [];

        // From meta frontmatter
        $meta = $this->getMetaForRoute($route);
        if (isset($meta[$key])) {
            $view = $meta[$key];
        }

        // From document frontmatter
        if (isset($frontmatter[$key])) {
            $view = array_merge_recursive_distinct($view, $frontmatter[$key]);
            //$view = array_merge($view, $frontmatter[$key]);
        }

        return $view;
    }



    /**
     * Get details on extra views.
     *
     * @param string $route       current route used to access page.
     * @param array  $frontmatter for the content.
     *
     * @return array with page data to send to view.
     */
    private function getViews($route, $frontmatter)
    {
        // Arrange data into views
        $views = $this->getView($route, $frontmatter, "views", true);

        // Set defaults
        if (!isset($views["main"]["template"])) {
            $views["main"]["template"] = $this->config["template"];
        }
        if (!isset($views["main"]["data"])) {
            $views["main"]["data"] = [];
        }

        // Merge remaining frontmatter into view main data.
        $data = $this->getMetaForRoute($route);
        unset($data["__toc__"]);
        unset($data["views"]);
        unset($frontmatter["views"]);

        if ($frontmatter) {
            $data = array_merge_recursive_distinct($data, $frontmatter);
        }
        $views["main"]["data"] = array_merge_recursive_distinct($views["main"]["data"], $data);

        return $views;
    }



    // == Create and load content ===================================

    /**
     * Map url to content, even internal content, if such mapping can be done.
     *
     * @param string $route route to look up.
     *
     * @return object with content and filtered version.
     */
    private function createContentForInternalRoute($route)
    {
        // Load index and map route to content
        $this->load("index");
        $this->load("meta");
        $this->load("author");
        $this->load("category");
        
        // Match the route
        $route = rtrim($route, "/");
        $route = $this->checkForMetaRoute($route);
        list($routeIndex, $content, $filtered) = $this->mapRoute2Content($route);

        // Create and arrange the content as views, merge with .meta,
        // frontmatter is complete.
        $content["views"] = $this->getViews($routeIndex, $filtered->frontmatter);

        // Do process content step two when all frontmatter is included.
        $this->processMainContentPhaseTwo($content, $filtered);
        
        // Set details of content
        $content["views"]["main"]["data"]["content"] = $filtered->text;
        $content["views"]["main"]["data"]["excerpt"] = $filtered->excerpt;
        $this->loadAdditionalContent($content["views"], $route, $routeIndex);

        // TODO Should not supply all frontmatter to theme, only the
        // parts valid to the index template. Separate that data into own
        // holder in frontmatter. Do not include whole frontmatter? Only
        // on debg?
        $content["frontmatter"] = $filtered->frontmatter;

        return (object) $content;
    }



    /**
     * Look up the route in the index and use that to retrieve the filtered
     * content.
     *
     * @param string $route to look up.
     *
     * @return array with content and filtered version.
     */
    private function mapRoute2Content($route)
    {
        // Look it up in the index
        list($keyIndex, $content) = $this->mapRoute2Index($route);
        $filtered = $this->loadFileContentPhaseOne($keyIndex);

        return [$keyIndex, $content, $filtered];
    }



    /**
     * Load content file and frontmatter, this is the first time we process
     * the content.
     *
     * @param string $key     to index with details on the route.
     *
     * @throws NotFoundException when mapping can not be done.
     *
     * @return void.
     */
    private function loadFileContentPhaseOne($key)
    {
        // Settings from config
        $basepath = $this->config["basePath"];
        $filter   = $this->config["textfilter-frontmatter"];

        // Whole path to file
        $path = $basepath . "/" . $this->index[$key]["file"];

        // Load content from file
        if (!is_file($path)) {
            $msg = t("The content '!ROUTE' does not exists as a file '!FILE'.", ["!ROUTE" => $key, "!FILE" => $path]);
            throw new \Anax\Exception\NotFoundException($msg);
        }

        // Get filtered content
        $src = file_get_contents($path);
        $filtered = $this->di->get("textfilter")->parse($src, $filter);

        return $filtered;
    }



    // == Process content phase 2 ===================================
    // TODO REFACTOR THIS?
    
    /**
     * Look up the route in the index and use that to retrieve the filtered
     * content.
     *
     * @param array  &$content   to process.
     * @param object &$filtered to use for settings.
     *
     * @return array with content and filtered version.
     */
    private function processMainContentPhaseTwo(&$content, &$filtered)
    {
        // From configuration
        $filter = $this->config["textfilter"];
        $revisionStart = $this->config["revision-history"]["start"];
        $revisionEnd   = $this->config["revision-history"]["end"];
        $revisionClass = $this->config["revision-history"]["class"];
        $revisionSource = isset($this->config["revision-history"]["source"])
            ? $this->config["revision-history"]["source"]
            : null;

        $textFilter = $this->di->get("textfilter");
        $text = $filtered->text;

        // Check if revision history is to be included
        if (isset($content["views"]["main"]["data"]["revision"])) {
            $text = $textFilter->addRevisionHistory(
                $text,
                $content["views"]["main"]["data"]["revision"],
                $revisionStart,
                $revisionEnd,
                $revisionClass,
                $revisionSource . "/" . $content["file"]
            );
        }

        // Get new filtered content (and updated frontmatter)
        // Title in frontmatter overwrites title found in content
        $new = $textFilter->parse($text, $filter);
        $filtered->text = $new->text;
         
        // Keep title if defined in frontmatter
        $title = isset($filtered->frontmatter["title"])
          ? $filtered->frontmatter["title"]
          : null;

        $filtered->frontmatter = array_merge_recursive_distinct(
            $filtered->frontmatter,
            $new->frontmatter
        );

        if ($title) {
            $filtered->frontmatter["title"] = $title;
        }

        // Main data is
        $data = &$content["views"]["main"]["data"];

        // Update all anchor urls to use baseurl, needs info about baseurl
        // from merged frontmatter
        $baseurl = isset($data["baseurl"])
          ? $data["baseurl"]
          : null;
        $this->addBaseurl2AnchorUrls($filtered, $baseurl);
        $this->addBaseurl2ImageSource($filtered, $baseurl);

        // Add excerpt and hasMore, if available
        $textFilter->addExcerpt($filtered);

        // Load details on author, if set.
        if (isset($data["author"])) {
            $data["author"] = $this->loadAuthorDetails($data["author"]);
        }

        // Load details on category, if set.
        if (isset($data["category"])) {
            $data["category"] = $this->loadCategoryDetails($data["category"]);
        }
    }



    // == Public methods ============================================
    
    /**
     * Map url to content, even internal content, if such mapping can be done.
     *
     * @param string $route optional route to look up.
     *
     * @return object with content and filtered version.
     */
    public function contentForInternalRoute($route = null)
    {
        // Get the route
        if (is_null($route)) {
            $route = $this->di->get("request")->getRoute();
        }

        // Check cache for content or create cached version of content
        $cache = $this->di->get("cache");
        $slug = $this->di->get("url")->slugify($route);
        $key = $cache->createKey(__CLASS__, "route-$slug");
        $content = $cache->get($key);

        if (!$content || $this->ignoreCache) {
            $content = $this->createContentForInternalRoute($route);
            $cache->set($key, $content);
        }

        return $content;
    }



    /**
     * Map url to content if such mapping can be done, exclude internal routes.
     *
     * @param string $route optional route to look up.
     *
     * @return object with content and filtered version.
     */
    public function contentForRoute($route = null)
    {
        $content = $this->contentForInternalRoute($route);
        if ($content->internal === true) {
            $msg = t("The content '!ROUTE' does not exists as a public route.", ["!ROUTE" => $route]);
            throw new NotFoundException($msg);
        }

        return $content;
    }
}
