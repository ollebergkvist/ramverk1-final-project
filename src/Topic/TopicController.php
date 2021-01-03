<?php

namespace Olbe19\Topic;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Topic\HTMLForm\CreateForm;
use Olbe19\Topic\HTMLForm\EditForm;
use Olbe19\Topic\HTMLForm\DeleteForm;
use Olbe19\Topic\HTMLForm\UpdateForm;
use Olbe19\Tag\Tag;
use Olbe19\Tag2Topic\Tag2Topic;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class TopicController implements ContainerInjectableInterface
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
            $session = $this->di->get("session");
            $value = $session->get("categoryID");
            $where = "category = ?";

            $page = $this->di->get("page");
            $topic = new Topic();
            $topic->setDb($this->di->get("dbqb"));

            $topicsInCategory = $topic->findAllWhere($where, $value);

            $tag2topic = new Tag2Topic();
            $tag2topic->setDb($this->di->get("dbqb"));


            $result = [];

            foreach ($topicsInCategory as $key => $value) {
                $tags = $tag2topic->findAllWhereJoin(
                    "Tag2Topic.topic = ?", // Where
                    $topicsInCategory[$key]->id, // Value
                    "Tags", // Table to join
                    "Tags.id = Tag2Topic.tag", // Join on
                    "Tag2Topic.*, Tags.name" // Select
                );

                array_push($result,
                [
                    "question" => $topicsInCategory[$key],
                    "tags" => $tags,
                    // "questionParsed" => $questionParsed,
                    // "answerCount" => $answerCount[0]->count,
                ]);
            }

            var_dump($result);

            // Topic Model
            // $topicModel = $this->di->get("topicmodel");
            // $nrOfPosts = $topicModel->getNumberOfPosts();

            // // Count number of topics
            // $sql = "SELECT COUNT(*) AS topic FROM Topics";
            // $db = $this->di->get("dbqb");
            // $db->connect();
            // $res = $db->executeFetch($sql);
            // $topics = $res->topic;
            // $number = range(1, $topics);

            // // Get number of posts in each topic
            // $sql = "SELECT COUNT(*) AS nrofposts FROM Posts WHERE topic = ?";
            // $db = $this->di->get("dbqb");
            // $postsArray = [];

            // foreach ($number as $item) {
            //     $param = [];
            //     array_push($param, $item);
            //     $db->connect();
            //     $res = $db->executeFetch($sql, $param);
            //     $nrOfPosts = $res->nrofposts;
            //     array_push($postsArray, $nrOfPosts);
            // }

            // var_dump($allTopics);

            // // Insert number of posts into topics table
            // $sql = "UPDATE Topics SET posts = ? WHERE id = ?";
            // $db = $this->di->get("dbqb");
            // $index = 1;

            // foreach ($postsArray as $item) {
            //     $params = [];
            //     array_push($params, $item, $index);
            //     $db->connect();
            //     $res = $db->execute($sql, $params);
            //     $index++;
            // }

            $page->add("topic/crud/view-all", [
                "items" => $result,
            ]);

            return $page->render([
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