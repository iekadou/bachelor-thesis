<?php
$START_TIME = microtime(true);
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Home', get_translation('Home'), 'index.html');
View::render();