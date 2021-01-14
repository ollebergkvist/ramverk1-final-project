<?php

namespace Olbe19\Topic;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Topic\HTMLForm\CreateForm;
use Olbe19\Topic\HTMLForm\DeleteForm;
use Olbe19\Topic\HTMLForm\UpdateForm;
use Olbe19\Post\Post;
use Olbe19\Tag\Tag;
use Olbe19\Tag2Topic\Tag2Topic;
use Olbe19\Gravatar\Gravatar;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TopicController implements ContainerInjectableInterface
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
        $this->topic = new Topic();
        $this->topic->setDb($this->di->get("dbqb"));
        $this->post = new Post();
        $this->post->setDb($this->di->get("dbqb"));
        $this->tag2topic = new Tag2Topic();
        $this->tag2topic->setDb($this->di->get("dbqb"));
        $this->session = $this->di->get("session");
        $this->page = $this->di->get("page");
        $this->gravatar = new Gravatar;
    }

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {   
        if (isset($_SESSION["permission"])) {
            // Get all topics in category
            $value = $this->session->get("categoryID");
            $topicsInCategory = $this->topic->getTopicsAndUserDetails($value);

            // Data to send to view
            $result = [];

            foreach ($topicsInCategory as $key => $value) {
                // SQL
                $where = "Tag2Topic.topic = ?";
                $value = $topicsInCategory[$key]->id;
                $table = "Tags";
                $join = "Tags.id = Tag2Topic.tag";
                $select = "Tag2Topic.*, Tags.name";

                // Get tag names
                $tags = $this->tag2topic->findAllWhereJoin(
                    $where, 
                    $value, 
                    $table, 
                    $join, 
                    $select 
                );

                // Get number of posts of a topic
                $nrOfPostsInTopic = $this->post->getNumberOfPostsOfTopic($topicsInCategory[$key]->id);

                // Push combined data to $result array
                array_push($result,
                [
                    "topic" => $topicsInCategory[$key],
                    "gravatar" => $this->gravatar->gravatar_image($topicsInCategory[$key]->email), 
                    "tags" => $tags,
                    "posts" => $nrOfPostsInTopic[0]->count,
                ]);
            }

            $this->page->add("topic/crud/view-all", [
                "items" => $result,
            ]);

            return $this->page->render([
                "title" => "Topics"
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to view posts connected to a topic.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function viewAction(int $id) 
    {
        if (isset($_SESSION["permission"])) {
            $session = $this->di->get("session");
            $session->set("topicID", $id);
            $this->di->get("response")->redirect("post");
        }
        // $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if (isset($_SESSION["permission"])) {
            $form = new CreateForm($this->di);
            $form->check();

            $this->page->add("topic/crud/create", [
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

            $this->page->add("topic/crud/delete", [
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

            $this->page->add("topic/crud/update", [
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
            $this->page->add("topic/crud/edit", [
                "items" => $this->topic->findAll(),
            ]);

            return $this->page->render([
                "title" => "Topics",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}