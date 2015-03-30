<?php
require_once($PATH."config/db.php");

global $QUERY_COUNT;
if (DB_PROFILER && !isset($QUERY_COUNT)) {
    $QUERY_COUNT = 0;
}
global $DB_CONNECTION_COUNT;
if (DB_PROFILER && !isset($DB_CONNECTION_COUNT)) {
    $DB_CONNECTION_COUNT = 0;
}

class DBConnector {

    private $db_connection = null;

    public function __construct() {
        if (DB_PROFILER) {
            global $DB_CONNECTION_COUNT;
            $DB_CONNECTION_COUNT++;
        }
        $this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function query($sql_statement) {
        if (DB_DEBUG) {
            echo 'QUERY: '.$sql_statement.'<br>';
        }
        if (DB_PROFILER) {
            global $QUERY_COUNT;
            $QUERY_COUNT++;
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
