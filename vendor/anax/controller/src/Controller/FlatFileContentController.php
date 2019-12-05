<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A controller for flat file markdown content.
 */
class FlatFileContentController implements ContainerInjectableInterface
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
        // Get the current route and see if it matches a content/file
        $path = $this->di->get("request")->getRoute();
        $file1 = ANAX_INSTALL_PATH . "/content/{$path}.md";
        $file2 = ANAX_INSTALL_PATH . "/content/{$path}/index.md";

        $file = is_file($file1) ? $file1 : null;
        $file = is_file($file2) ? $file2 : $file;
        
        if (!$file) {
            return;
        }

        // Check that file is really in the right place
        $real = realpath($file);
        $base = realpath(ANAX_INSTALL_PATH . "/content/");
        if (strncmp($base, $real, strlen($base))) {
            return;
        }

        // Get content from markdown file
        $content = file_get_contents($file);
        $content = $this->di->get("textfilter")->parse(
            $content,
            ["frontmatter", "variable", "shortcode", "markdown", "titlefromheader"]
        );

        // Add content as a view and then render the page
        $page = $this->di->get("page");
        $page->add("anax/v2/article/default", [
            "content" => $content->text,
            "frontmatter" => $content->frontmatter,
        ]);

        return $page->render($content->frontmatter);
    }
}
