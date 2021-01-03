<?php

namespace Olbe19\Topic;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Topic\HTMLForm\CreateForm;
use Olbe19\Topic\HTMLForm\EditForm;
use Olbe19\Topic\HTMLForm\DeleteForm;
use Olbe19\Topic\HTMLForm\UpdateForm;
use Olbe19\Post\Post;
use Olbe19\Tag\Tag;
use Olbe19\Tag2Topic\Tag2Topic;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TopicController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->post = new Post();
        $this->post->setDb($this->di->get("dbqb"));
        $this->tag2topic = new Tag2Topic();
        $this->tag2topic->setDb($this->di->get("dbqb"));
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
        if (isset($_SESSION['username'])) {
            // Init topic and connect to db
            $topic = new Topic();
            $topic->setDb($this->di->get("dbqb"));

            // Get all topics in category
            $value = $this->session->get("categoryID");
            $where = "category = ?";
            $topicsInCategory = $topic->findAllWhere($where, $value);

            // Get number of topics
            $nrOfTopics = $topic->getNumberOfTopics();

            // Get number of posts of a topic
            $nrOfPostsOfTopic = $this->post->getNumberOfPostsOfTopic("1");

            // Data to send to view
            $result = [];

            foreach ($topicsInCategory as $key => $value) {
                $tags = $this->tag2topic->findAllWhereJoin(
                    "Tag2Topic.topic = ?", // Where
                    $topicsInCategory[$key]->id, // Value
                    "Tags", // Table to join
                    "Tags.id = Tag2Topic.tag", // Join on
                    "Tag2Topic.*, Tags.name" // Select
                );

                array_push($result,
                [
                    "topic" => $topicsInCategory[$key],
                    "tags" => $tags,
                    "posts" => $posts,
                ]);
            }

            $this->page->add("topic/crud/view-all", [
                "items" => $result,
            ]);

            return $this->page->render([
                "title" => "Forum | Topics"
            ]);
        }
        // $this->di->get("response")->redirect("user/login");
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
        if (isset($_SESSION['username'])) {
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
        if (isset($_SESSION['username'])) {
            $page = $this->di->get("page");
            $form = new CreateForm($this->di);
            $form->check();

            $page->add("topic/crud/create", [
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
        if (isset($_SESSION['username'])) {
            $page = $this->di->get("page");
            $form = new DeleteForm($this->di);
            $form->check();

            $page->add("topic/crud/delete", [
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
        if (isset($_SESSION['username'])) {
            $page = $this->di->get("page");
            $form = new UpdateForm($this->di, $id);
            $form->check();

            $page->add("topic/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Update an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}