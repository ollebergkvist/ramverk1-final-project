<?php

namespace Olbe19\About;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class AboutController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        if (isset($_SESSION['username'])) {
            $page = $this->di->get("page");
            $title = "About";

            $page->add("about/about");

            return $page->render([
                "title" => $title,
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}