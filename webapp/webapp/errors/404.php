<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('404', getTranslation('404'), 'errors/404.php');
View::render();
