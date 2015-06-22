<?php

namespace Iekadou\Webapp;

use mysqli;

if (DB_PROFILER) {
    Globals::set_var('QUERY_COUNT', 0);
}
if (DB_PROFILER) {
    Globals::set_var('DB_CONNECTION_COUNT', 0);
}

class DBConnector {
    private $db_connection = null;

    public function __construct() {
        require_once(PATH."config/db.php");
        if (DB_PROFILER) {
            Globals::set_var('DB_CONNECTION_COUNT', Globals::get_var('DB_CONNECTION_COUNT') + 1);
        }
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (DB_CACHING) {
            $this->db_connection->query("SET SESSION query_cache_type=1;");
        } else {
            $this->db_connection->query("SET SESSION query_cache_type=0;");
        }
    }

    public function query($sql_statement) {
        if (DB_DEBUG) {
            echo 'QUERY: '.$sql_statement.'<br>';
        }
        if (DB_PROFILER) {
            Globals::set_var('QUERY_COUNT', Globals::get_var('QUERY_COUNT') + 1);
        }
        return $this->db_connection->query($sql_statement);
    }

    public function get_connect_errno() {
        return $this->db_connection->connect_errno;
    }

    public function real_escape_string($string) {
        return $this->db_connection->real_escape_string($string);
    }

    public function autocommit($value) {
        return $this->db_connection->autocommit($value);
    }

    public function commit() {
        return $this->db_connection->commit();
    }

    public function get_error() {
        return $this->db_connection->error;
    }

    public function rollback() {
        return $this->db_connection->rollback();
    }

    public function get_insert_id() {
        return $this->db_connection->insert_id;
    }
}
