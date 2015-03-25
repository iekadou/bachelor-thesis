<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Imprint', getTranslation('Imprint'), "endpoints/imprint.php");
View::render();
