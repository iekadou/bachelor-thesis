<?php
require_once("../inc/include.php");

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

new View('Imprint', Translation::translate('Imprint'), "imprint.html");
View::render();
