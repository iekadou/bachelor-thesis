<?php
include("../inc/path.php");
include($PATH."inc/include.php");
new View();

$userid = (isset($_POST['userid']) ? htmlspecialchars($_POST['userid']) : false);
if ($userid != View::get_account()->get_user_id()) {
    $userid = false;
    $errors[] = "userid";
}

$username = (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : false);
if (!preg_match("/^[a-zA-Z0-9 ]{3,50}$/", $username)) {
    $errors[] = "username";
}
$User = new User();
if ($User->count_by(array(array("username", "=", $username), array("id", "!=", $userid))) > 0) {
    $errors[] = "username";
}

$email = (isset($_POST['email']) ? htmlspecialchars($_POST['email']) : false);
if (!preg_match('/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_\-]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/', $email)) {
    $errors[] = "email";
}
if ($User->count_by(array(array("email", "=", $username), array("id", "!=", $userid))) > 0) {
    $errors[] = "email";
}

$password = (isset($_POST['password']) ? htmlspecialchars($_POST['password']) : false);
if ($password != '' && !preg_match("/^[a-zA-Z0-9 ]{3,50}$/", $password)) {
    $errors[] = "password";
}
$password_again = (isset($_POST['password_again']) ? htmlspecialchars($_POST['password_again']) : false);
if ($password_again != '' && !preg_match("/^[a-zA-Z0-9 ]{3,50}$/", $password_again)) {
    $errors[] = "password_again";
}
if ($password != $password_again) {
    $errors[] = "password";
    $errors[] = "password_again";
}
if (!empty($errors)) {
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
    require_once($PATH."classes/User.php");
    $User = new User();
    $User = $User->get($userid);
    $User = $User->set_username($username)->set_email($email);
    if ($password != '' && $password != false) {
        $User = $User->set_password($password);
    }
    $User->save();
    echo '{"url": "/account/"}';
}