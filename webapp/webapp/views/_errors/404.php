<?php

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

require_once("../../inc/include.php");
new View('404', Translation::translate('404'), 'errors/404.html');
View::render();
