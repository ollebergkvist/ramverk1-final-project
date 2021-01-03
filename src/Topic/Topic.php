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

    public function getPostsByAuthor($value): array
    {
        $where = "author = ?";

        return $this->findAllWhere(
            $where, 
            $value, 
        );
    }
}