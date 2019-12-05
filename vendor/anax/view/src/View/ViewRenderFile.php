<?php

namespace Anax\View;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Render a view based on a template file and a dataset.
 */
class ViewRenderFile implements
    ViewRenderFileInterface,
    ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * Render the view file.
     *
     * @param string $file to include as view file.
     * @param array  $data to expose within the view.
     *
     * @throws \Anax\View\Exception when template file is not found.
     *
     * @return void
     */
    public function render(string $file, array $data) : void
    {
        if (!is_readable($file)) {
            throw new Exception("Could not find template file: " . $this->template);
        }

        $di = $this->di;
        $app = null;
        if ($di->has("app")) {
            $app = $di->get("app");
        }
        extract($data);
        require $file;
    }
}
