<?php

namespace Olbe19\Tag;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * A database driven model using the Active Record design pattern.
 */
class Tag extends ActiveRecordModel
{
    /**
     * @var string $tableName name of the database table.
     */
    protected $tableName = "Tags";

    /**
     * Columns in the table.
     *
     * @var integer $id primary key auto incremented.
     */
    public $id;
    public $name;

    public function getTagNamesById($di, $id): array
    {
        $db = $di->get("db");
        $db->connect();
        $sql = "SELECT name FROM Tags WHERE id = $id";
        
        $result = $db->executeFetchAll($sql);

        return $result;
    }


    public function getTagById($value)
    {
        $where = "id";

        return $this->find(
            $where, 
            $value, 
        );
    }
}