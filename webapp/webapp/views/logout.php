<?php
require_once("../inc/include.php");

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

new View('Logout', Translation::translate('Logout'), "logout.html");
View::get_account()->logout();
View::render();
