<?php

namespace Anax\View;

use Anax\View\Exception;
use Anax\View\ViewRenderFile;
use Psr\Container\ContainerInterface;

/**
 * A view connected to a template file, supporting Anax DI.
 */
class View
{
    /**
     * @var $template     Template file or array
     * @var $templateData Data to send to template file
     * @var $sortOrder    For sorting views
     * @var $type         Type of view
     */
    private $template;
    private $templateData = [];
    private $sortOrder;
    private $type;



    /**
     * Set values for the view.
     *
     * @param array|string|callable $template the template file, array
     *                                        or callable
     * @param array        $data     variables to make available to the
     *                               view, default is empty
     * @param integer      $sort     which order to display the views,
     *                               if suitable
     * @param string       $type     which type of view
     *
     * @return self
     */
    public function set($template, $data = [], $sort = 0, $type = "file")
    {
        if (empty($template)) {
            $type = "empty";
        } elseif (is_array($template)) {
            if (isset($template["callback"])) {
                $type = "callback";
                $this->template = $template["callback"];
            } else {
                $this->template = $template["template"];
            }

            $this->sortOrder = $template["sort"] ?? $sort;
            $this->type = $template["type"] ?? $type;

            // Merge data array
            $data1 = $template["data"] ?? [];
            if (empty($data)) {
                $this->templateData = $data1;
            } else if (empty($data1)) {
                $this->templateData = $data;
            } else {
                foreach ($data as $key => $val) {
                    if (is_array($val)) {
                        if (!array_key_exists($key, $data1)) {
                            $data1[$key] = [];
                        }
                        $data1[$key] = array_merge($data1[$key], $val);
                    } else {
                        $data1[$key] = $val;
                    }
                    $this->templateData = $data1;
                }
            }

            return;
        }

        $this->template     = $template;
        $this->templateData = $data;
        $this->sortOrder    = $sort;
        $this->type         = $type;

        return $this;
    }



    /**
     * Render the view by its type.
     *
     * @param object $di optional with access to the framework resources.
     *
     * @return void
     */
    public function render(ContainerInterface $di = null)
    {
        switch ($this->type) {
            case "file":
                if ($di->has("viewRenderFile")) {
                    $viewRender = $di->get("viewRenderFile");
                } else {
                    $viewRender = new ViewRenderFile($di);
                    $viewRender->setDI($di);
                }
                $viewRender->render($this->template, $this->templateData);
                break;

            case "callback":
                if (!is_callable($this->template)) {
                    throw new Exception("View is expecting a valid callback, provided callback seems to not be a callable.");
                }
                echo call_user_func($this->template, $this->templateData);
                break;

            case "string":
                echo $this->template;
                break;

            case "empty":
                break;

            default:
                throw new Exception("Not a valid template type: '{$this->type}'.");
        }
    }



    /**
     * Give the sort order for this view.
     *
     * @return int
     */
    public function sortOrder()
    {
        return $this->sortOrder;
    }
}
