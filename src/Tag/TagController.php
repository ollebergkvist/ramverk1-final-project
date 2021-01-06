<?php

namespace Olbe19\Tag;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Tag\HTMLForm\CreateForm;
use Olbe19\Tag\HTMLForm\DeleteForm;
use Olbe19\Tag\HTMLForm\UpdateForm;
use Olbe19\Tag2Topic\Tag2Topic;
use Olbe19\Post\Post;
use Olbe19\User\User;
use Olbe19\Gravatar\Gravatar;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TagController implements ContainerInjectableInterface
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
        $this->tag = new Tag();
        $this->tag->setDb($this->di->get("dbqb"));
        $this->post = new Post();
        $this->post->setDb($this->di->get("dbqb"));
        $this->user = new User();
        $this->user->setDb($this->di->get("dbqb"));
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
        $this->page = $this->di->get("page");

        $this->page->add("tag/view-all", [
            "items" => $this->tag->findAll(),
        ]);

        return $this->page->render([
            "title" => "Tags",
        ]);
    }

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function viewActionGet($id) : object
    {   
        // SQL
        $where = "Tag2Topic.tag = ?";
        $value = $id;
        $table = "Topics";
        $join = "Topics.id = Tag2Topic.topic";
        $select = "Tag2Topic.tag, Topics.*";

        $tagName = $this->tag->getTagById($id)->name;

        // Get topics connected to a specific tag
        $topicsInTag = $this->tag2topic->findAllWhereJoin(
            $where, 
            $value, 
            $table, 
            $join, 
            $select 
        );

        // Result array
        $result = [];

        foreach ($topicsInTag as $key => $value ) {
            // Get number of posts of a topic
            $nrOfPostsInTopic = $this->post->getNumberOfPostsOfTopic($topicsInTag[$key]->id);

            // Get email of user
            $email = $this->user->getEmailByName("celine");
            
            // Create gravatar
            $gravatar = $this->gravatar->gravatar_image($email->email);

            // Push combined data to $result array
            array_push($result,
            [
                "topic" => $topicsInTag[$key],
                "gravatar" => $gravatar, 
                "tagName" => $tagName,
                "posts" => $nrOfPostsInTopic[0]->count,
            ]);
        }

        $this->page->add("tag/view-topics-in-tag", [
            "items" => $result,
        ]);

        return $this->page->render([
            "title" => "Topics in tag",
        ]);
    }

    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if ($_SESSION['permission'] === "admin") {
            $page = $this->di->get("page");
            $form = new CreateForm($this->di);
            $form->check();

            $page->add("tag/crud/create", [
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

            $page->add("tag/crud/delete", [
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

            $page->add("tag/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Update an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}