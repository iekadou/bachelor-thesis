<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");
new View('Register', getTranslation('Register'), "endpoints/register.php");
View::render();
