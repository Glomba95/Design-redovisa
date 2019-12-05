<?php

namespace Anax\Page;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * To ease rendering a page consisting of several views.
 */
class Page implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var array $layout to hold tha layout view to be rendered last.
     */
    private $layout;



    /**
     * Set the view to be used for the layout.
     *
     * @param array $view configuration to create up the view.
     *
     * @return $this
     */
    public function addLayout(array $view) : object
    {
        $this->layout = $view;
        return $this;
    }



    /**
     * Utility method to add a view to the view collection for later
     * rendering.
     *
     * @param array|string  $template the name of the template file to include
     *                                or array with view details.
     * @param array         $data     variables to make available to the view,
     *                                default is empty.
     * @param string        $region   which region to attach the view, default
     *                                is "main".
     * @param integer       $sort     which order to display the views.
     *
     * @return $this
     */
    public function add(
        $template,
        array $data = [],
        string $region = "main",
        int $sort = 0
    ) : object {
        $this->di->get("view")->add($template, $data, $region, $sort);
        return $this;
    }



    /**
     * Add the layout view to the region "layout and render all views
     * within the region "layout", and create a response from it.
     *
     * @param array   $data   additional variables to expose to layout view.
     * @param integer $status code to use when delivering the result.
     *
     * @return object
     */
    public function render(array $data = [], int $status = 200)
    {
        $view = $this->di->get("view");
        $view->add($this->layout, $data, "layout");
        $body = $view->renderBuffered("layout");

        $response = $this->di->get("response");
        $response->setBody($body);
        $response->setStatusCode($status);
        return $response;
    }
}
