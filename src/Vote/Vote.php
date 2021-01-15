<?php

namespace Olbe19\Vote;

use Olbe19\ActiveRecordExtended\ActiveRecordExtended;
// use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Vote extends ActiveRecordExtended
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Vote";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $user;
    public $post;
    public $vote;

    public function checkUserVote($postID, $username)
    {   $where = "post = ? AND user = ?";

        $result = $this->findWhere($where, [$postID, $username]);
        
        if ($result->id == null) {
            return false;
        }
        return true;
    }

    public function getVote($postID, $username)
    {   
        $where = "post = ? AND user = ?";
        
        $result = $this->findWhere($where, [$postID, $username]);

        return $result->vote;
    }

    public function getUserVotes($value): object
    {   
        $select = "count(user) as count";
        $where = "user = ?";

        return $this->findWhere(
            $where, 
            $value, 
            $select
        );
    }
}