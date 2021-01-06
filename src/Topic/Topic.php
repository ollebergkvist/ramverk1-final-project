<?php

namespace Olbe19\Topic;

// use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Olbe19\ActiveRecordExtended\ActiveRecordExtended;

/**
 * A database driven model using the Active Record design pattern.
 */
class Topic extends ActiveRecordExtended
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

    public function getNumberOfTopics(): array
    {
        $select = "count(*) as count";

        $topics = $this->findAll($select);

        return $topics;
    }

    public function getTopicsByAuthor($value): array
    {
        $where = "author = ?";

        return $this->findAllWhere(
            $where, 
            $value, 
        );
    }

    public function getTopicsAndUserDetails($value): array
    {   
        $order = "date DESC";
        $table = "User";
        $join = "Topics.author = User.username";
        $limit = "1000";
        $select = "Topics.*, User.username, User.email";

        $topics = $this->findAllWhereJoinOrder(
            $order, 
            $table, 
            $join, 
            $value,
            $limit, 
            $select,
        );

        return $topics;
    }

    public function getUserDetailsSingleTopic($value): array
    {   
        $order = "date DESC";
        $table = "User";
        $join = "Topics.author = User.username";
        $limit = "1000";
        $select = "Topics.*, User.username, User.email";

        $topics = $this->findWhereJoin(
            $order, 
            $table, 
            $join, 
            $value,
            $limit, 
            $select,
        );

        return $topics;
    }


    public function findWhereJoin($where, $value, $select = "*") : object
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        $this->db->connect()
                    ->select($select)
                    ->from($this ->tableName)
                    ->where($where)
                    ->join($joinTable, $joinOn)
                    ->execute($params)
                    ->fetchInto($this);
        return $this;
    }
}