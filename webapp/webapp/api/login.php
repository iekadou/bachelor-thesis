<?php
include("../inc/include.php");

use Iekadou\Webapp\View as View;
use Iekadou\Webapp\User as User;
new View();

require_once(PATH."classes/User.php");
$User = new User();

$identification = (isset($_POST['identification']) ? htmlspecialchars($_POST['identification']) : false);
$login_user = false;

if ($identification == false || $identification == '') {
    $errors[] = "identification";
} else {
    $login_user = $User->get_by(array(array('username', '=', $identification)));
    if ($login_user == false) {
        $login_user = $User->get_by(array(array('email', '=', $identification)));
    }
    if ($login_user == false) {
       $errors[] = "identification";
    }
}
$password = (isset($_POST['password']) ? htmlspecialchars($_POST['password']) : false);
if ($password == false || $password == '') {
    $errors[] = "password";
}
if ($login_user == false || $password == false) {
    echo stringify_errors($errors);
} else {
    $loggedIn = View::get_account()->login($login_user, $password);
    if (is_array($loggedIn)) {
        echo stringify_errors($loggedIn);
    } else {
        echo '{"url": "/account/"}';
    }
}
