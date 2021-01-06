<?php

namespace Olbe19\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Post\HTMLForm\CreateForm;
use Olbe19\Post\HTMLForm\DeleteForm;
use Olbe19\Post\HTMLForm\UpdateForm;
use Olbe19\DatabaseQuery\DataBaseQuery;
use Olbe19\Filter\Markdown;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        if (isset($_SESSION['permission'])) {
            $session = $this->di->get("session");
            $value = $session->get("topicID");
            $where = "topic = ?";

            $page = $this->di->get("page");
            $post = new Post();
            $post->setDb($this->di->get("dbqb"));

            $page->add("post/crud/view-all", [
                "items" => $post->findAllWhere($where, $value),
            ]);

            return $page->render([
                "title" => "Forum | Posts"
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if (isset($_SESSION['permission'])) {
            $page = $this->di->get("page");
            $form = new CreateForm($this->di);
            $form->check();

            $page->add("post/crud/create", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Create a item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        if ($_SESSION['permission'] === "admin") {
            $page = $this->di->get("page");
            $form = new DeleteForm($this->di);
            $form->check();

            $page->add("post/crud/delete", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Delete an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        if ($_SESSION['permission'] === "admin") {
            $page = $this->di->get("page");
            $form = new UpdateForm($this->di, $id);
            $form->check();

            $page->add("post/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Update an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}