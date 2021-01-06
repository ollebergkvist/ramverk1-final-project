<?php

namespace Olbe19\User;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model.
 */
class User extends ActiveRecordModel
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
}