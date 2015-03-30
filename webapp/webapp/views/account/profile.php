<?php
include("../../inc/path.php");
require_once($PATH."inc/include.php");
new View('Profile', get_translation('Profile'), "account/profile.html");
if (View::get_account()->is_logged_in() != true) {
    raise404();
    die();
}
View::set_template_var('profile_active', "active");
View::render();
