<?php
include("../../inc/path.php");
require_once($PATH . "inc/include.php");
new View('404', get_translation('404'), 'errors/404.html');
View::render();
