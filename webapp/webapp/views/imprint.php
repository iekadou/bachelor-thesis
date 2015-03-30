<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Imprint', get_translation('Imprint'), "imprint.html");
View::render();
