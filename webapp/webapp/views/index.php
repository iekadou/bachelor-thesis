<?php
require_once("../inc/include.php");

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

new View('Home', Translation::translate('Home'), 'index.html');
View::render();