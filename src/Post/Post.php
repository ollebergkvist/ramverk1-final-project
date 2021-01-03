<?php

namespace Olbe19\Post;

// use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Olbe19\ActiveRecordExtended\ActiveRecordExtended;

/**
 * A database driven model using the Active Record design pattern.
 */
class Post extends ActiveRecordExtended
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Posts";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $content;
    public $date;
    public $topic;
    public $author;

    public function getNumberOfPostsOfTopic($where): array
    {
        $topic = "topic = ?";
        $count = "count(*) as count";

        return $this->findAllWhere(
            $topic, 
            $where, 
            $count
        );
    }
}