<?php
require_once("../../inc/include.php");

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

new View('Profile', Translation::translate('Profile'), "account/profile.html");
if (View::get_account()->is_logged_in() != true) {
    raise404();
    die();
}
View::set_template_var('profile_active', "active");
View::render();
