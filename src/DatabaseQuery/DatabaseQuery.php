<?php

namespace Olbe19\DatabaseQuery;

use Anax\DatabaseQueryBuilder\DatabaseQueryBuilder;
use Anax\Database\Database;

class DatabaseQuery extends Database
{
    private $db;

    /**
     * Method to connect to db.
     *
     * @return void
     */
    public function dbConnect()
    {
        $this->db = new DatabaseQueryBuilder([
            "dsn"              => "sqlite:" . ANAX_INSTALL_PATH . "/data/db.sqlite",
            "username"         => null,
            "password"         => null,
            "driver_options"   => null,
            "fetch_mode"       => \PDO::FETCH_OBJ,
            "table_prefix"     => null,
            "session_key"      => "Anax\Database",
            "emulate_prepares" => false,

            // True to be very verbose during development
            "verbose"         => null,

            // True to be verbose on connection failed
            "debug_connect"   => false,
        ]);

        $this->db->setDefaultsFromConfiguration();

        // Connect
        $db = $this->db->connect();

        return $db;
    }

    /**
     * Method to select with inner join.
     *
     * @return $result
     */
    public function dbInnerJoinQuery()
    {
        $this->db->select("Posts.*, Topics.subject")
                    ->from('Posts')
                    ->where('topic = 1')
                    ->join('Posts.topic = Topics.id');

        $result = $this->db->getSQL();

        return $result;
    }

    /**
     * Execute a SQL-query.
     *
     * @param string|null|array $query  the SQL statement (or $params)
     * @param array             $params the params array
     *
     * @return self
     */
    public function execute($query = null, array $params = []) : object
    {
        // When using one argument and its array, assume its $params
        if (is_array($query)) {
            $params = $query;
            $query = null;
        }

        if (!$query) {
            $query = $this->getSQL();
        }

        return parent::execute($query, $params);
    }
}