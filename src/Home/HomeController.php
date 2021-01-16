<?php

namespace Olbe19\Home;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Home\Home;
use Olbe19\Topic\Topic;
use Olbe19\Tag\Tag;
use Olbe19\Tag2Topic\Tag2Topic;
use Olbe19\Post\Post;
use Olbe19\User\User;
use Olbe19\Gravatar\Gravatar;
use Olbe19\Filter\Markdown;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class HomeController implements ContainerInjectableInterface
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
        $this->topic = new Topic();
        $this->topic->setDb($this->di->get("dbqb"));
        $this->post = new Post();
        $this->post->setDb($this->di->get("dbqb"));
        $this->tag = new Tag();
        $this->tag->setDb($this->di->get("dbqb"));
        $this->tag2topic = new Tag2Topic();
        $this->tag2topic->setDb($this->di->get("dbqb"));
        $this->user = new User();
        $this->user->setDb($this->di->get("dbqb"));
        $this->gravatar = new Gravatar();
        $this->markdown = new Markdown();
        $this->page = $this->di->get("page");
    }

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet(): object
    {   
        if (isset($_SESSION['username'])) {
            $nrOfItems = 3;
            $latestTopics = $this->topic->getTopicsAndUserDetails2($nrOfItems);
            $popularTags = $this->tag2topic->getMostPopularTags($nrOfItems);
            $mostActiveUsers = $this->user->getMostActiveUser($nrOfItems);

            // Array to send to view
            $result = [];

            foreach ($latestTopics as $key => $value) {
                // SQL
                $where = "Tag2Topic.topic = ?";
                $value = $latestTopics[$key]->id;
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
                $nrOfPostsInTopic = $this->post->getNumberOfPostsOfTopic($latestTopics[$key]->id);

                // Push combined data to $result array
                array_push($result,
                [
                    "topic" => $latestTopics[$key],
                    "gravatar" => $this->gravatar->gravatar_image($latestTopics[$key]->email), 
                    "tags" => $tags,
                    "posts" => $nrOfPostsInTopic[0]->count,
                ]);
            }

            // Array to send to view
            $result2 = [];

            foreach ($mostActiveUsers as $key => $value) {
                // Push combined data to $result array
                array_push($result2,
                [
                    "user" => $mostActiveUsers[$key],
                    "gravatar" => $this->gravatar->gravatar_image($mostActiveUsers[$key]->email), 
                ]);
            }
            
            $this->page->add("home/home", [
                "popularTopics" => $result,
                "popularTags" => $popularTags,
                "mostActiveUsers" => $result2,
            ]);

            return $this->page->render([
                "title" => "Home",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}