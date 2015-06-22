<?php

namespace Iekadou\Webapp;

abstract class BaseModel
{
    protected $db_connection = null;
    protected $id = null;

    public function __construct()
    {
        global $DB_CONNECTOR;
        foreach($this->fields as $field) {
            $this->$field = "";
        }
        if (!isset($DB_CONNECTOR)) {
            $DB_CONNECTOR = new DBConnector();
        }
        $this->db_connection = $DB_CONNECTOR;
        if ($this->db_connection->get_connect_errno()) {
            $this->errors[] = "db";
        }
    }

    public function get($id)
    {
        $id = $this->db_connection->real_escape_string(htmlentities($id, ENT_QUOTES));
        $obj_query = $this->db_connection->query("SELECT * FROM ".$this->table." WHERE id = '" . $id . "';");
        if ($obj_query->num_rows == 1) {
            $obj = $obj_query->fetch_object();
            foreach($this->fields as $field) {
                $this->$field = $obj->$field;
            }
            $this->id = $obj->id;
            return $this;
        }
        return false;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function save()
    {
        $update_str = '';
        $i = 0;
        foreach($this->fields as $field) {
            if ($i > 0) {
                $update_str .= ", ";
            }
            $i++;
            $update_str .= $field." = '".$this->$field."'";
        }
        $obj_query = $this->db_connection->query("UPDATE ".$this->table." set ".$update_str." WHERE id = '" . $this->id . "';");
        if ($obj_query) {
            return $this;
        }
        return false;
    }

    public function create()
    {
        $insert_str = '(';
        $i = 0;
        foreach($this->fields as $field) {
            if ($i > 0) {
                $insert_str .= ", ";
            }
            $i++;
            $insert_str .= $field;
        }
        $insert_str .= ') VALUES (';
        $i = 0;
        foreach($this->fields as $field) {
            if ($i > 0) {
                $insert_str .= ", ";
            }
            $i++;
            if (!isset($this->$field)) {
                $this->$field = '';
            }
            $insert_str .= "'".$this->$field."'";
        }

        $insert_str .= ')';
        $obj_query = $this->db_connection->query("INSERT INTO ".$this->table." ".$insert_str.";");
        if ($obj_query) {
            $this->id = $this->db_connection->get_insert_id();
            return $this;
        }

        echo $this->db_connection->get_error();
        return false;
    }

    public function delete($userid)
    {
        $userid = $this->db_connection->real_escape_string(htmlentities($userid, ENT_QUOTES));
        $user_query = $this->db_connection->query("DELETE FROM ".$this->table." WHERE id = '" . $userid . "';");
        if ($user_query) {
            return true;
        }
        return false;
    }

    public function count_by($conditions, $sortings=array())
    {
        $condition_str = '';
        $i = 0;
        foreach($conditions as $condition) {
            if ($i > 0) {
                $condition_str .= ' and ';
            } else {
                $condition_str = "WHERE ";
            }
            if ($condition[0] == 'id' || in_array($condition[0], $this->fields)) {
                $i++;
                $condition_str .= $condition[0]." ".$condition[1]." '".$this->db_connection->real_escape_string(htmlentities($condition[2], ENT_QUOTES))."'";
            }
        }
        $order_str = '';
        $i = 0;
        foreach($sortings as $sorting) {
            if ($i > 0) {
                $order_str .= ', ';
            } else {
                $order_str = 'ORDER BY ';
            }
            if ($sorting[0] == 'id' || in_array($sorting[0], $this->fields)) {
                $i++;
                $order_str .= $sorting[0]." ".$sorting[1];
            }
        }
        $obj_count = $this->db_connection->query("SELECT count(*) as count FROM ".$this->table." ".$condition_str." ".$order_str.";");
        $obj = $obj_count->fetch_object();
        return $obj->count;
    }

    public function get_by($conditions, $sortings=array())
    {
        $condition_str = '';
        $i = 0;
        foreach($conditions as $condition) {
            if ($i > 0) {
                $condition_str .= ' and ';
            } else {
                $condition_str = "WHERE ";
            }
            if ($condition[0] == 'id' || in_array($condition[0], $this->fields)) {
                $i++;
                $condition_str .= $condition[0]." ".$condition[1]." '".$this->db_connection->real_escape_string(htmlentities($condition[2], ENT_QUOTES))."'";
            }
        }
        $order_str = '';
        $i = 0;
        foreach($sortings as $sorting) {
            if ($i > 0) {
                $order_str .= ', ';
            } else {
                $order_str = 'ORDER BY ';
            }
            if ($sorting[0] == 'id' || in_array($sorting[0], $this->fields)) {
                $i++;
                $order_str .= $sorting[0]." ".$sorting[1];
            }
        }
        $obj_query = $this->db_connection->query("SELECT * FROM ".$this->table." ".$condition_str." ".$order_str.";");
        if ($obj_query && $obj_query->num_rows == 1) {
            $obj = $obj_query->fetch_object();
            foreach($this->fields as $field) {
                $this->$field = $obj->$field;
            }
            $this->id = $obj->id;
            return $this;
        }
        return false;
    }
    
    public function filter_by($conditions=array(), $sortings=array())
    {
        $condition_str = '';
        $i = 0;
        foreach($conditions as $condition) {
            if ($i > 0) {
                $condition_str .= ' and ';
            } else {
                $condition_str = "WHERE ";
            }
            if ($condition[0] == 'id' || in_array($condition[0], $this->fields)) {
                $i++;
                $condition_str .= $condition[0]." ".$condition[1]." '".$this->db_connection->real_escape_string(htmlentities($condition[2], ENT_QUOTES))."'";
            }
        }
        $order_str = '';
        $i = 0;
        foreach($sortings as $sorting) {
            if ($i > 0) {
                $order_str .= ', ';
            } else {
                $order_str = 'ORDER BY ';
            }
            if ($sorting[0] == 'id' || in_array($sorting[0], $this->fields)) {
                $i++;
                $order_str .= $sorting[0]." ".$sorting[1];
            }
        }
        $obj_array = Array();
        $obj_query = $this->db_connection->query("SELECT * FROM ".$this->table." ".$condition_str." ".$order_str.";");
        if ($obj_query) {
            while ($row = $obj_query->fetch_object()) {
                $class = get_class($this);
                $obj = new $class();
                foreach($obj->fields as $field) {
                    $obj->$field = $row->$field;
                }
                $obj->id = $row->id;
                $obj_array[] = $obj;
            
            }
            return $obj_array;
        }
        return false;
    }

}
