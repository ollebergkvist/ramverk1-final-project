<?php

namespace Olbe19\Tag2Topic;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag2Topic extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tag2topic";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $tag;
    public $topic;

    public function getTagsForTopic($di, $topic): array
    {
        $db = $di->get("dbqb");
        $db->connect();
        $sql = "SELECT tag FROM Tag2Topic WHERE topic = $topic";
        
        $result = $db->executeFetchAll($sql);

        return $result;
    }
}