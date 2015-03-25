<?php
include("../../inc/path.php");
require_once($PATH."inc/include.php");
new View('Profile', getTranslation('Profile'), "endpoints/account/profile.php");
if (View::get_account()->is_logged_in() != true) {
    raise404();
    die();
}
$profile_active = "active";
View::render();
