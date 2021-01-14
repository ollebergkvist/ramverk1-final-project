<?php

namespace Olbe19\Post;

use Olbe19\ActiveRecordExtended\ActiveRecordExtended;
use Olbe19\Vote\Vote;

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
    public $rank;
    public $accepted;

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

    public function getPostsByAuthor($value): array
    {
        $where = "author = ?";

        return $this->findAllWhere(
            $where, 
            $value, 
        );
    }

    public function acceptPost($postID)
    {
        $post = $this->findById($postID); 
        $this->id = $post->id;
        $this->accepted = true;
        return $this->updateWhere("id = ?", $postID);
    }

    public function voteAnswer($postID, $getVote, $username, $di)
    {
        $vote = new Vote();
        $vote->setDb($di->get("dbqb"));

        $vote->post = $postID;
        $vote->user = $username;
        $vote->vote = $getVote;

        $ifVoted = $vote->checkUserVote($postID, $username);

        $post = $this->findById($postID);
        $this->savePost($post);

        if ($ifVoted) {
            $where = "post = ? AND user = ?";
            $fetch = $vote->findWhere($where, [$postID, $username]);
            
            $priorVote = $fetch->vote;

            if ($this->checkPriorVote($priorVote, $getVote, $postID, $username, $di)) {
                return;
            }
            
            $this->calculatePoints($getVote);
            $this->saveVote($postID, $username, $getVote, $di); 
        } else {
            $this->calculatePoints($getVote);
            $this->saveVote($postID, $username, $getVote, $di);
        }
        return $this->updateWhere("id = ?", $postID);
    }

    public function checkPriorVote($priorVote, $getVote, $postID, $username, $di)
    {
        if ($priorVote === "up-vote" && $getVote === "up-vote") {
            $this->rank = $this->rank -1;
            $this->deleteVote($postID, $username, $di);
            $this->updateWhere("id = ?", $postID);
            return true;
        } else if ($priorVote === "down-vote" && $getVote === "down-vote") {
            $this->rank = $this->rank +1;
            $this->deleteVote($postID, $username, $di);
            $this->updateWhere("id = ?", $postID);
            return true;
        } else if ($priorVote === "up-vote") {
            $this->rank = $this->rank -1;
        } else if ($priorVote === "down-vote") {
            $this->rank = $this->rank +1;
        }
        $this->updateWhere("id = ?", $postID);
        $this->deleteVote($postID, $username, $di);
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

    public function savePost($post)
    {   
        $this->id = $post->id;
        $this->content = $post->content;
        $this->date = $post->date;
        $this->topic = $post->topic;
        $this->author = $post->author;
        $this->rank = $post->rank;
        $this->accepted = $post->accepted;
    }

    public function saveVote($postID, $username, $getVote, $di)
    {   
        $vote = new Vote();
        $vote->setDb($di->get("dbqb"));
        $vote->user = $username;
        $vote->post = $postID;
        $vote->vote = $getVote;
        $vote->save();
    }

    public function deleteVote($postID, $username, $di)
    {
        $vote = new Vote();
        $vote->setDb($di->get("dbqb"));
        $where = "post = ? AND user = ?";
        $vote->deleteWhere($where, [$postID, $username]);
    }

    public function findAllOrder($where, $value, $order): array
    {   
        $this->checkDb();
        
        $select = "Posts.*";
        $table = "User";
        $join = "User.username = Posts.author";
        $params = [$value];
        
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->where($where)
            ->join($table, $join)
            ->orderBy($order)
            ->execute($params)
            ->fetchAllClass(get_class($this));
    }
}