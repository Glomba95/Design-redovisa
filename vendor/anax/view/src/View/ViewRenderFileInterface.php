<?php

namespace Anax\View;

/**
 * Render a view based on a template file and a dataset.
 */
interface ViewRenderFileInterface
{
    /**
     * Render the view file and expose the data to it.
     *
     * @param string $file to include as view file.
     * @param array  $data to expose within the view.
     *
     * @throws \Anax\View\Exception when template file is not found.
     *
     * @return void
     */
    public function render(string $file, array $data) : void;
}
