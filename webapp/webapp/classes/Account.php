<?php

require_once($PATH."classes/DBConnector.php");
require_once($PATH."classes/User.php");


class Account
{
    private $db_connection = null;
    private $user = null;

    public function __construct()
    {
        global $DB_CONNECTOR;
        if (!isset($DB_CONNECTOR)) {
            $DB_CONNECTOR = new DBConnector();
        }
        $this->db_connection = $DB_CONNECTOR;
        if ($this->db_connection->get_connect_errno()) {
            $this->errors[] = "db";
        }
    }

    public function login($user_obj, $password)
    {
        if (password_verify($password, $user_obj->password)) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user_obj->get_id();

            $this->user_is_logged_in = true;
            return true;
        } else {
            $this->errors[] = "password";
            return $this->errors;
        }
    }

    public function logout()
    {
        $_SESSION = array();
        session_destroy();
        $this->user_is_logged_in = false;
        return true;
    }

    public function is_logged_in()
    {
        if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] == true) {
           return true;
        }
        return false;
    }

    public function get_user_id()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        return 0;
    }

    public function get_user()
    {
        if (isset($this->user)) {
            return $this->user;
        } else {
            $User = new User();
            $this->user = $User->get($this->get_user_id());
            return $this->user;
        }
    }
}
