<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Logout', get_translation('Logout'), "logout.html");
View::get_account()->logout();
View::render();
