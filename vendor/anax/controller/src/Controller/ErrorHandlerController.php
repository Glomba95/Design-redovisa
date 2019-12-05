<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Route\Exception\NotFoundException;

/**
 * A controller to ease with development and debugging information.
 */
class ErrorHandlerController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * Add internal routes for 403, 404 and 500 that provides a page with
     * error information, using the default page layout.
     *
     * @param string $message with details.
     *
     * @throws Anax\Route\Exception\NotFoundException

     * @return object as the response.
     */
    public function catchAll(...$args) : object
    {
        $title = " | Anax";
        $pages = [
            "403" => [
                "Anax 403: Forbidden",
                "You are not permitted to do this."
            ],
            "404" => [
                "Anax 404: Not Found",
                "The page you are looking for is not here."
            ],
            "500" => [
                "Anax 500: Internal Server Error",
                "An unexpected condition was encountered."
            ],
        ];

        $path = $this->di->get("router")->getMatchedPath();
        if (!array_key_exists($path, $pages)) {
            throw new NotFoundException("Internal route for '$path' is not found.");
        }

        $page = $this->di->get("page");
        $page->add(
            "anax/v2/error/default",
            [
                "header" => $pages[$path][0],
                "text" => $pages[$path][1],
            ]
        );

        return $page->render([
            "title" => $pages[$path][0] . $title
        ], $path);
    }
}
