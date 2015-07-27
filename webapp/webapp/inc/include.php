<?php
$RENDERING_START = microtime(true);
// get request method
$REQUEST_METHOD = "GET";
if (isset($_POST['_method']) && ($_POST['_method'] == 'GET' || $_POST['_method'] == 'POST' || $_POST['_method'] == 'PUT' || $_POST['_method'] == 'DELETE')) {
    $REQUEST_METHOD = $_POST['_method'];
}

session_start();

define('PATH', '/Users/jonas/bachelor-thesis/webapp/webapp/');
define('Lare', true);

// configs
include(PATH."config/webapp.php");

if (TEMPLATE_CACHING) {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}

require_once(PATH."vendor/autoload.php");
require_once(PATH."classes/Globals.php");
require_once(PATH."classes/DBConnector.php");

// includes
include(PATH."inc/utils.php");
require_once(PATH."inc/translations.php");
require_once(PATH."classes/View.php");
require_once(PATH."classes/UrlsPy.php");
require_once(PATH."classes/Pagination.php");
require_once(PATH."classes/TwigUrl.php");
require_once(PATH."classes/TwigTrans.php");
require_once(PATH."classes/TwigTime.php");
