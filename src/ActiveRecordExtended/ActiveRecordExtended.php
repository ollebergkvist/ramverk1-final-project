<?php

namespace Olbe19\ActiveRecordExtended;

use Anax\DatabaseActiveRecord\ActiveRecordModel;

/**
 * An implementation of the Active Record pattern to be used as
 * base class for database driven models.
 */
class ActiveRecordExtended extends ActiveRecordModel
{
    /**
     * Find and return all.
     *
     * @return array of object of this class
     */
    public function findAll($select = "*") 
    {
        $this->checkDb();
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
                        ->execute()
                        ->fetchAllClass(get_class($this));
    }

    /**
     * Find and return all matching the search criteria.
     *
     * The search criteria `$where` of can be set up like this:
     *  `id = ?`
     *  `id IN [?, ?]`
     *
     * The `$value` can be a single value or an array of values.
     *
     * @param string $where to use in where statement.
     * @param mixed  $value to use in where statement.
     *
     * @return array of object of this class
     */
    public function findAllWhere($where, $value, $select = "*")
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
                        ->select($select)
                        ->from($this->tableName)
                        ->where($where)
                        ->execute($params)
                        ->fetchAllClass(get_class($this));
    }

    public function findAllWhereJoin($where, $value, $joinTable, $joinOn, $select = "*")
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
            ->select($select ?? null)
            ->from($this->tableName)
            ->where($where)
            ->join($joinTable, $joinOn)
            ->execute($params)
            ->fetchAllClass(get_class($this));
    }

    public function findAllWhereJoinOrder($order, $table, $join, $value, $limit = 100, $select = "*")
    {
        $this->checkDb();
        $params = is_array($value) ? $value : [$value];
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->orderBy($order)
            ->where("category = ?")
            ->join($table, $join)
            ->limit($limit)
            ->execute($params)
            ->fetchAllClass(get_class($this));
    }

    public function findAllJoinOrder($order, $table, $join, $limit, $select)
    {
        $this->checkDb();
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->orderBy($order)
            ->join($table, $join)
            ->limit($limit)
            ->execute()
            ->fetchAllClass(get_class($this));
    }

    public function findAllJoinOrderGroup($order, $group, $table, $join, $limit = 10000, $select = "*")
    {
        $this->checkDb();
        return $this->db->connect()
            ->select($select)
            ->from($this->tableName)
            ->groupBy($group)
            ->orderBy($order)
            ->join($table, $join)
            ->limit($limit)
            ->execute()
            ->fetchAllClass(get_class($this));
    }
}