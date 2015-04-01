<?php
require_once("../inc/include.php");

use Iekadou\Webapp\View, Iekadou\Webapp\Translation;

new View('Register', Translation::translate('Register'), "register.html");
View::render();
