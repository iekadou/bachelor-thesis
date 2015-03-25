<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Logout', getTranslation('Logout'), "endpoints/logout.php");
View::get_account()->logout();
View::render();
