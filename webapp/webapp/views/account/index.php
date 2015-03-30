<?php
include("../../inc/path.php");
require_once($PATH."inc/include.php");
new View('Dashboard', get_translation('Dashboard'), "account/index.html");
if (View::get_account()->is_logged_in() != true) {
    raise404();
    die();
}
View::set_template_var('dashboard_active', "active");
View::render();
