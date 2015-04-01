<?php
include("../inc/include.php");

use Iekadou\Webapp\User as User;
use Iekadou\Webapp\Account as Account;

$code_string = (isset($_POST['code_string']) ? htmlspecialchars($_POST['code_string']) : false);
$username = (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : false);
if (!preg_match("/^[a-zA-Z0-9 ]{3,50}$/", $username)) {
    $errors[] = "username";
}
$email = (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : false);
if (!preg_match('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_\-]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email)) {
    $errors[] = "email";
}
$password = (isset($_POST['password']) ? htmlspecialchars($_POST['password']) : false);
if (!preg_match("/^[a-zA-Z0-9 ]{3,50}$/", $password)) {
    $errors[] = "password";
}
$password_repeat = (isset($_POST['password_repeat']) ? htmlspecialchars($_POST['password_repeat']) : false);
if (!preg_match("/^[a-zA-Z0-9 ]{3,50}$/", $password_repeat)) {
    $errors[] = "password_repeat";
}

if ($password != $password_repeat) {
    $password = false;
    $password_repeat = false;
    $errors[] = "password";
    $errors[] = "password_repeat";
}
if ($username == false || $email == false || $password == false) {
    $error_output = "{";
    foreach ($errors as $error) {
        if ($error_output != "{") {
            $error_output .= ",";
        }
        $error_output .= '"'.$error.'": "error"';
    }
    $error_output .= "}";
    echo $error_output;
} else {
    $User = new User();
    $User = $User->set_username($username)->set_email($email)->set_password($password);
    if (!$User) {
        $error_output = "{";
        foreach ($errors as $error) {
            if ($error_output != "{") {
                $error_output .= ",";
            }
            $error_output .= '"'.$error.'": "error"';
        }
        $error_output .= "}";
        echo $error_output;
    } else {
        $User = $User->create();
        if (!$User==false && $Account->login($User, $password)) {
            echo '{"url": "/account/"}';
        } else {
            echo '{"error_msgs": [["login failed", "internal error"]]}';
        }
    }
}
