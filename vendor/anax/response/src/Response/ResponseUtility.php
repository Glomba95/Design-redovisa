<?php

namespace Anax\Response;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Handling a response and includes utilitie methods.
 */
class ResponseUtility extends Response implements
    ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * Redirect to another page and creating an url from the argument.
     *
     * @param string $url to redirect to
     *
     * @return self
     */
    public function redirect(string $url) : object
    {
        return parent::redirect($this->di->get("url")->create($url));
    }



    /**
     * Redirect to current page.
     *
     * @return self
     */
    public function redirectSelf() : object
    {
        $url = $this->di->get("request")->getCurrentUrl();
        return parent::redirect($this->di->get("url")->create($url));
    }
}
