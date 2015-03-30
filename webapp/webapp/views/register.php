<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Register', get_translation('Register'), "register.html");
View::render();
