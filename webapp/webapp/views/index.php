<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Home', getTranslation('Home'), 'endpoints/index.php');
View::render();
