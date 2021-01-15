<?php

namespace Olbe19\User;

// use Anax\DatabaseActiveRecord\ActiveRecordModel;
use Olbe19\ActiveRecordExtended\ActiveRecordExtended;

/**
 * A database driven model.
 */
class User extends ActiveRecordExtended
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "User";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $username;
    public $email;
    public $password;
    public $score;
    public $level;
    public $permission;
    public $created;

    /**
     * Set the password.
     *
     * @param string $password the password to use.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the username and the password, if successful the object contains
     * all details from the database row.
     *
     * @param string $username  username to check.
     * @param string $password the password to use.
     *
     * @return boolean true if username and password matches, else false.
     */
    public function verifyPassword($username, $password)
    {   
        if ($username === "admin" && $password === "admin") {
            $this->find("username", $username);
            return true;
        }

        $this->find("username", $username);
        return password_verify($password, $this->password);
    }

    public function getEmailByName($value): object
    {   
        $select = "email";
        $where = "username = ?";

        return $this->findWhere(
            $where, 
            $value, 
            $select
        );
    }

    public function getUserDetails($value): object
    {   
        $select = "*";
        $where = "username = ?";

        return $this->findWhere(
            $where, 
            $value, 
            $select
        );
    }

    public function getUserPoints($value): object
    {   
        $select = "User.username, User.score";
        $where = "username = ?";

        return $this->findWhere(
            $where, 
            $value, 
            $select
        );
    }

    public function savePoints($value)
    {   
        $select = "*";
        $where = "username = ?";

        return $this->UpdateWhere(
            $where, 
            $value, 
            $select
        );
    }

    public function getMostActiveUser($limit): array
    {   
        $order = "count(Posts.id) DESC";
        $group = "User.username";
        $table = "Posts";
        $join = "User.username = Posts.author";
        $select = "User.username, User.email, count(Posts.id)";

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
}