<?php

namespace Olbe19\Vote2Topic;

use Olbe19\ActiveRecordExtended\ActiveRecordExtended;
// use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Vote2Topic extends ActiveRecordExtended
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Vote2Topic";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user;
    public $topic;
    public $vote;

    public function checkUserVote($topicID, $username)
    {   $where = "topic = ? AND user = ?";

        $result = $this->findWhere($where, [$topicID, $username]);
        
        if ($result->id == null) {
            return false;
        }
        return true;
    }

    public function getVote($topicID, $username)
    {   
        $where = "topic = ? AND user = ?";
        
        $result = $this->findWhere($where, [$topicID, $username]);

        return $result->vote;
    }
}