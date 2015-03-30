<?php
$RENDERING_START = microtime(true);
// get request method
$REQUEST_METHOD = "GET";
if (isset($_POST['_method']) && ($_POST['_method'] == 'GET' || $_POST['_method'] == 'POST' || $_POST['_method'] == 'PUT' || $_POST['_method'] == 'DELETE')) {
    $REQUEST_METHOD = $_POST['_method'];
}

session_start();

// configs
include($PATH."config/webapp.php");

require_once($PATH."vendor/autoload.php");

// includes
include($PATH."inc/translations.php");
include($PATH."inc/utils.php");
require_once($PATH."classes/View.php");
require_once($PATH."classes/DBConnector.php");
require_once($PATH."classes/UrlsPy.php");
