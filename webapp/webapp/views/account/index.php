<?php
include("../../inc/path.php");
require_once($PATH."inc/include.php");
new View('Dashboard', getTranslation('Dashboard'), "endpoints/account/index.php");
if (View::get_account()->is_logged_in() != true) {
    raise404();
    die();
}
$dashboard_active = "active";
View::render();
