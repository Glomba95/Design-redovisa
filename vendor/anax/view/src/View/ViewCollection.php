<?php

namespace Anax\View;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A view collection supporting Anax DI, store all views per region,
 * render at will.
 */
class ViewCollection implements
    ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var array $views container for all views.
     */
    private $views = [];



    /**
     * @var array  $paths  where to look for template files.
     * @var string $suffix add to each template file name.
     */
    private $paths = [];
    private $suffix = ".php";



    /**
     * Set paths to search through when looking for template files.
     *
     * @param array $paths with directories to search through.
     *
     * @return self
     */
    public function setPaths(array $paths) : object
    {
        foreach ($paths as $path) {
            if (!(is_dir($path) && is_readable($path))) {
                throw new Exception("Directory '$path' is not readable.");
            }
        }
        $this->paths = $paths;
        return $this;
    }



    /**
     * Set suffix to add last to template file givven, as a filename extension.
     *
     * @param string $suffix to use as file extension.
     *
     * @return self
     */
    public function setSuffix(string $suffix) : object
    {
        $this->suffix = $suffix;
        return $this;
    }



    /**
     * Convert template to path to template file and check that it exists.
     *
     * @param string $template the name of the template file to include
     *
     * @throws Anax\View\Exception when template file is missing
     *
     * @return string as path to the template file
     */
    public function getTemplateFile($template)
    {
        $file = $template . $this->suffix;
        if (is_file($file)) {
            return $file;
        }

        foreach ($this->paths as $path) {
            $file = $path . "/" . $template . $this->suffix;
            if (is_file($file)) {
                return $file;
            }
        }

        throw new Exception("Could not find template file '$template'.");
    }



    /**
     * Add (create) a view to be included, pass optional data and put the
     * view in an optional specific region (default region is "main") and
     * pass an optional sort value where the highest value is rendered first.
     * The $template can be a:
     *  filename (string),
     *  callback (array with key callback set to a callable array),
     *  view array (key value array with template, data, region, sort)
     *
     * @param array|string  $template the name of the template file to include.
     * @param array         $data     variables to make available to the view,
     *                                default is empty.
     * @param string        $region   which region to attach the view, default
     *                                is "main".
     * @param integer       $sort     which order to display the views.
     *
     * @return self for chaining.
     */
    public function add(
        $template,
        array $data = [],
        string $region = "main",
        int $sort = 0
    ) : object {
        $view = new View();

        if (empty($template)) {
            $tpl = null;
            $type = "empty";
        } elseif (is_string($template)) {
            $tpl = $this->getTemplateFile($template);
            $type = "file";
        } elseif (is_array($template)) {
            // Can be array with complete view or array with callable callback
            $tpl = $template;
            $type = "empty";
            $region = $tpl["region"] ?? $region;

            if (isset($tpl["callback"])) {
                $tpl["template"] = $template;
                $tpl["type"] = "callback";
            } elseif (isset($tpl["template"])) {
                if (!isset($tpl["type"]) || $tpl["type"] === "file") {
                    $tpl["type"] = "file";
                    $tpl["template"] = $this->getTemplateFile($tpl["template"]);
                }
            }
        }

        $view->set($tpl, $data, $sort, $type);
        $this->views[$region][] = $view;

        return $this;
    }



    /**
     * Add a callback to be rendered as a view.
     *
     * @param string $callback function to call to get the content of the view
     * @param array  $data     variables to make available to the view, default is empty
     * @param string $region   which region to attach the view
     * @param int    $sort     which order to display the views
     *
     * @return $this
     */
    public function addCallback($callback, $data = [], $region = "main", $sort = 0)
    {
        $view = new View();
        $view->set(["callback" => $callback], $data, $sort, "callback");
        $this->views[$region][] = $view;

        return $this;
    }



    /**
     * Add a string as a view.
     *
     * @param string $content the content
     * @param string $region  which region to attach the view
     * @param int    $sort    which order to display the views
     *
     * @return $this
     */
    public function addString($content, $region = "main", $sort = 0)
    {
        $view = new View();
        $view->set($content, [], $sort, "string");
        $this->views[$region][] = $view;
        
        return $this;
    }



    /**
     * Check if a region has views to render.
     *
     * @param string $region which region to check
     *
     * @return $this
     */
    public function hasContent($region)
    {
        return isset($this->views[$region]);
    }



    /**
     * Render all views for a specific region.
     *
     * @param string $region which region to use
     *
     * @return void
     */
    public function render($region = "main")
    {
        if (!isset($this->views[$region])) {
            return $this;
        }

        mergesort($this->views[$region], function ($viewA, $viewB) {
            $sortA = $viewA->sortOrder();
            $sortB = $viewB->sortOrder();

            if ($sortA == $sortB) {
                return 0;
            }

            return $sortA < $sortB ? -1 : 1;
        });

        foreach ($this->views[$region] as $view) {
            $view->render($this->di);
        }
    }


    /**
     * Render all views for a specific region and buffer the result.
     *
     * @param string $region which region to use.
     *
     * @return string with the buffered results.
     */
    public function renderBuffered($region = "main")
    {
        ob_start();
        $this->render($region);
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
    }
}
