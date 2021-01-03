<?php

namespace Olbe19\Topic;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * A database driven model using the Active Record design pattern.
 */
class TopicModel implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function getNumberOfPosts(): array
    {
        // Count number of topics
        $sql = "SELECT COUNT(*) AS topic FROM Topics";
        $db = $this->di->get("dbqb"); 
        $db->connect();
        $res = $db->executeFetch($sql);
        $topics = $res->topic;
        $number = range(1, $topics);

        // Get number of posts in each topic
        $sql = "SELECT COUNT(*) AS nrofposts FROM Posts WHERE topic = ?";
        $db = $this->di->get("dbqb");
        $postsArray = [];

        foreach ($number as $item) {
            $param = [];
            array_push($param, $item);
            $db->connect();
            $res = $db->executeFetch($sql, $param);
            $nrOfPosts = $res->nrofposts;
            array_push($postsArray, $nrOfPosts);
        }

        return $number;
    }
}