<?php

namespace Olbe19\Topic;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Topic extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Topics";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $subject;
    public $date;
    public $category;
    public $author;

    public function getNumberOfPosts(): array
    {
        // Count number of topics
        $sql = "SELECT COUNT(*) AS topic FROM Topics";
        $db = $di->get("dbqb");
        $db->connect();
        $result = $db->executeFetch($sql);
        $topics = $result->topic;
        $number = range(1, $topics);

        // Get number of posts in each topic
        $sql = "SELECT COUNT(*) AS nrofposts FROM Posts WHERE topic = ?";
        $db = $di->get("dbqb");
        $postsArray = [];

        foreach ($number as $item) {
            $param = [];
            array_push($param, $item);
            $db->connect();
            $result = $db->executeFetch($sql, $param);
            $nrOfPosts = $result->nrofposts;
            array_push($postsArray, $nrOfPosts);
        }

        return $postsArray;
    }
}