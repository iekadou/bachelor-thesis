<?php

namespace Iekadou\Webapp;

class User extends BaseModel
{
    protected $table = 'user';
    protected $fields = array('username', 'email', 'password', 'activated', 'activation_key');
    
    public function get_username()
    {
        return $this->username;
    }
    
    public function set_username($username)
    {
        $username = $this->db_connection->real_escape_string(htmlentities($username, ENT_QUOTES));
        $this->username = $username;
        return $this;
    }

    public function get_email()
    {    
        return $this->email;
    }

    public function set_email($email)
    {    
        $email = $this->db_connection->real_escape_string(htmlentities($email, ENT_QUOTES));
        $this->email = $email;
        return $this;
    }

    public function set_password($password)
    {
        $password = $this->db_connection->real_escape_string(htmlentities($password, ENT_QUOTES));
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function register_new_user($username, $email, $password)
    {

        $this->user_name = $this->db_connection->real_escape_string(htmlentities($username, ENT_QUOTES));
        $this->user_email = $this->db_connection->real_escape_string(htmlentities($email, ENT_QUOTES));

        $this->user_password = $this->db_connection->real_escape_string(htmlentities($password, ENT_QUOTES));
        $this->user_password_hash = password_hash($this->user_password, PASSWORD_DEFAULT);

        $query_check_user_name = $this->db_connection->query("SELECT * FROM user WHERE username = '" . $this->user_name . "';");
        if ($query_check_user_name->num_rows == 1) {
            $this->errors[] = "username";
        }
        $query_check_user_user_email = $this->db_connection->query("SELECT * FROM user WHERE email = '" . $this->user_email . "';");
        if ($query_check_user_user_email->num_rows == 1) {
            $this->errors[] = "email";
        }
        if (!isset($this->errors) || empty($this->errors)) {
            $this->activation_key = $this->generate_activation_key();
            $query_new_user_insert = $this->db_connection->query("INSERT INTO user (username, password, email, activated, activation_key) VALUES('" . $this->user_name . "', '" . $this->user_password_hash . "', '" . $this->user_email . "', '0', '".$this->activation_key."');");
            if ($query_new_user_insert) {
                $user_id = $this->db_connection->get_insert_id();
                if ($this->send_activation_email()) {
                    return $user_id;
                } else {
                    $this->errors[] = 'activation';
                }
            }
        }
        if (empty($this->errors)) {
            $this->errors[] = 'some';
        }
        return $this->errors;
    }

    private function generate_activation_key() {
        $activation_key = '';
        while ($activation_key == '') {
            for($length = 0; $length < 20; $length++) {
                $chr_cat = rand(0,1);
                switch ($chr_cat) {
                    case 0:
                        $char = chr(rand(50,57));
                        break;
                    default:
                        $char = chr(rand(97,122));
                }
                $activation_key .= $char;
            }
            $query_activation_key = $this->db_connection->query("SELECT * FROM user WHERE activation_key = '" . $activation_key . "';");
            if ($query_activation_key->num_rows > 0) {
                $activation_key = '';
            }
        }
        $this->activation_key = $activation_key;
    }
    
    public function send_activation_email() {
        $subject = 'Your account at Lare.io';
        $content = 'Hey '.$this->user_name.',
you can activate your account by clicking the following link:
https://lare.io/activate/'.$this->activation_key.'

Thank you for using Lare.io';
        $header = 'From: noreply@lare.io';
        if (mail($this->user_email, $subject, $content, $header)) { 
            return true; 
        } else {
            return false; 
        }
    }
}
