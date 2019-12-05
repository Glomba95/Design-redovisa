<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Route\Exception\NotFoundException;

/**
 * A controller to ease with development and debugging information.
 */
class DevelopmentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * Render views that are supported.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @throws Anax\Route\Exception\NotFoundException when route is not found.

     * @return object as the response.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args) : object
    {
        $pages = [
            "" => "index",
            "di" => "di",
            "request" => "request",
            "router" => "router",
            "session" => "session",
            "session/increment" => "session_increment",
            "session/destroy" => "session_destroy",
            "view" => "view",
        ];

        $path = $this->di->get("router")->getMatchedPath();

        if (!array_key_exists($path, $pages)) {
            throw new NotFoundException("No such page '$path' in the development controller.");
        }

        $page = $this->di->get("page");
        $page->add(
            "anax/v2/dev/{$pages[$path]}",
            [
                "mount" => "dev/"
            ]
        );

        return $page->render([
            "title" => ucfirst($pages[$path]),
            "baseTitle" => " | Anax development utilities"
        ]);
    }
}
