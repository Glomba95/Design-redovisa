<?php

namespace Anax\Content;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Route\Exception\NotFoundException;

/**
 * A controller for flat file markdown content.
 */
class FileBasedContentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * Render a page using flat file content.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return mixed as null when flat file is not found and otherwise a
     *               complete response object with content to render.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        $content = $this->di->get("content");
        $page = $this->di->get("page");

        try {
            $fileContent = $content->contentForRoute();
        } catch(NotFoundException $e) {
            return false;
        }

        foreach ($fileContent->views as $view) {
            $page->add($view);
        }

        return $page->render($fileContent->frontmatter);
    }
}
