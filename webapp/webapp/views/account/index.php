<?php
require_once("../../inc/include.php");

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

new View('Dashboard', Translation::translate('Dashboard'), "account/index.html");
if (View::get_account()->is_logged_in() != true) {
    raise404();
    die();
}
View::set_template_var('dashboard_active', "active");
View::render();
