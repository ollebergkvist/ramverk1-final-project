<?php

namespace Olbe19\Admin;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class AdminController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {   
        if ($_SESSION['permission'] === "admin") {
            $page = $this->di->get("page");
            $title = "admin";

            $page->add("admin/dashboard");

            return $page->render([
                "title" => $title,
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}