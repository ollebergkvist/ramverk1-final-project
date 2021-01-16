<?php

namespace Olbe19\Post;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Post\HTMLForm\CreateForm;
use Olbe19\Post\HTMLForm\DeleteForm;
use Olbe19\Post\HTMLForm\UpdateForm;
use Olbe19\Topic\Topic;
use Olbe19\DatabaseQuery\DataBaseQuery;
use Olbe19\Filter\Markdown;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class PostController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convenient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize(): void
    {   
        $this->post = new Post();
        $this->post->setDb($this->di->get("dbqb"));
        $this->topic = new Topic();
        $this->topic->setDb($this->di->get("dbqb"));
        $this->request = $this->di->get("request");
        $this->session = $this->di->get("session");
        $this->page = $this->di->get("page");
    }

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {   
        if (isset($_SESSION['permission'])) {
            $value = $this->session->get("topicID");
            $where = "topic = ?";
            $where2 = "id = ?";

            $order = $this->request->getGet("order") ?? "date";

            $this->page->add("post/crud/view-all", [
                "items" => $this->post->findAllOrder($where, $value, $order),
                "topic"=> $this->topic->findWhereJoin2($where2, "User", "User.username = Topics.author", $value, "Topics.*, User.email"),
            ]);

            return $this->page->render([
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
            $form = new CreateForm($this->di);
            $form->check();

            $this->page->add("post/crud/create", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
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
            $form = new DeleteForm($this->di);
            $form->check();

            $this->page->add("post/crud/delete", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
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
            $form = new UpdateForm($this->di, $id);
            $form->check();

            $this->page->add("post/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Update an item",
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
    public function editAction() : object
    {
        if ($_SESSION['permission'] === "admin") {
            $this->page->add("post/crud/edit", [
                "items" => $this->post->findAll(),
            ]);

            return $this->page->render([
                "title" => "Posts",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Mark post as accepted.
     *
     * @return object as a response object
     */
    public function acceptAction()
    {
        if (isset($_SESSION['permission'])) {
            // Get data from form
            $username = $this->session->get("username");
            $postID = $this->request->getGet("postID");

            $this->post->findById($postID);

            if ($this->post->author !== $username) {
                $this->post->acceptPost($postID);
            }
            
            $this->di->get("response")->redirect("post")->send();
        }
        // $this->di->get("response")->redirect("user/login");
    }

    /**
     * Vote on post.
     *
     * @return object as a response object
     */
    public function voteAction()
    {   
        if (isset($_SESSION['permission'])) {
            // Get data from form
            $postID = $this->request->getGet("postID");
            $getVote = $this->request->getGet("vote");
            $username = $this->request->getGet("username");
            
            $this->post->voteAnswer($postID, $getVote, $username, $this->di);

            $this->di->get("response")->redirect("post")->send();
        }
        // $this->di->get("response")->redirect("user/login");
    }
}