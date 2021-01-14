<?php

namespace Olbe19\Topic;

use Olbe19\ActiveRecordExtended\ActiveRecordExtended;
use Olbe19\Vote2Topic\Vote2Topic;

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
    public $content;
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

    public function getTopicsAndUserDetails($value, $limit = "1000"): array
    {   
        $order = "date DESC";
        $table = "User";
        $join = "Topics.author = User.username";
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

    public function getTopicsAndUserDetails2($limit): array
    {   
        $order = "date DESC";
        $group = "Topics.date";
        $table = "User";
        $join = "Topics.author = User.username";
        $select = "Topics.*, User.username, User.email";

        $topics = $this->findAllJoinOrderGroup(
            $order, 
            $group,
            $table, 
            $join, 
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

    public function getNumberOfTopicsInCategory($where): array
    {
        $select = "category = ?";
        $count = "count(*) as count";

        return $this->findAllWhere(
            $select, 
            $where, 
            $count
        );
    }

    public function voteAnswer($topicID, $getVote, $username, $di)
    {
        $vote2topic = new Vote2Topic();
        $vote2topic->setDb($di->get("dbqb"));

        $vote2topic->topic = $topicID;
        $vote2topic->user = $username;
        $vote2topic->vote = $getVote;

        $ifVoted = $vote2topic->checkUserVote($topicID, $username);

        $topic = $this->findById($topicID);
        $this->saveTopic($topic);

        if ($ifVoted) {
            $where = "topic = ? AND user = ?";
            $fetch = $vote2topic->findWhere($where, [$topicID, $username]);
            
            $priorVote = $fetch->vote;

            if ($this->checkPriorVote($priorVote, $getVote, $topicID, $username, $di)) {
                return;
            }
            
            $this->calculatePoints($getVote);
            $this->saveVote($topicID, $username, $getVote, $di); 
        } else {
            $this->calculatePoints($getVote);
            $this->saveVote($topicID, $username, $getVote, $di);
        }
        return $this->updateWhere("id = ?", $topicID);
    }

    public function checkPriorVote($priorVote, $getVote, $topicID, $username, $di)
    {
        if ($priorVote === "up-vote" && $getVote === "up-vote") {
            $this->rank = $this->rank -1;
            $this->deleteVote($topicID, $username, $di);
            $this->updateWhere("id = ?", $topicID);
            return true;
        } else if ($priorVote === "down-vote" && $getVote === "down-vote") {
            $this->rank = $this->rank +1;
            $this->deleteVote($topicID, $username, $di);
            $this->updateWhere("id = ?", $topicID);
            return true;
        } else if ($priorVote === "up-vote") {
            $this->rank = $this->rank -1;
        } else if ($priorVote === "down-vote") {
            $this->rank = $this->rank +1;
        }
        $this->updateWhere("id = ?", $topicID);
        $this->deleteVote($topicID, $username, $di);
    }

    public function calculatePoints($getVote)
    {   
        $arg = $getVote;
        
        if ($arg === "up-vote") {
            $this->rank = $this->rank +1;
        } 

        elseif ($arg === "down-vote") {
            $this->rank = $this->rank -1;
        }
    }

    public function saveTopic($topic)
    {   
        $this->id = $topic->id;
        $this->subject = $topic->subject;
        $this->content = $topic->content;
        $this->date = $topic->date;
        $this->category = $topic->category;
        $this->author = $topic->author;
        $this->rank = $topic->rank;
    }

    public function saveVote($topicID, $username, $getVote, $di)
    {   
        $vote2topic = new Vote2Topic();
        $vote2topic->setDb($di->get("dbqb"));
        $vote2topic->user = $username;
        $vote2topic->topic = $topicID;
        $vote2topic->vote = $getVote;
        $vote2topic->save();
    }

    public function deleteVote($topicID, $username, $di)
    {
        $vote2topic = new Vote2Topic();
        $vote2topic->setDb($di->get("dbqb"));
        $where = "topic = ? AND user = ?";
        $vote2topic->deleteWhere($where, [$topicID, $username]);
    }
}