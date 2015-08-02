<?php
$RENDERING_START = microtime(true);
// get request method
$REQUEST_METHOD = "GET";
if (isset($_POST['_method']) && ($_POST['_method'] == 'GET' || $_POST['_method'] == 'POST' || $_POST['_method'] == 'PUT' || $_POST['_method'] == 'DELETE')) {
    $REQUEST_METHOD = $_POST['_method'];
}

session_start();

define('PATH', '/Users/jonas/bachelor-thesis/angular_webapp/webapp/');

require_once(PATH."classes/DBConnector.php");

if (isset($_GET['tpl_caching']) && $_GET['tpl_caching'] == 'true') {
    $_SESSION['tpl_caching'] = true;
}
if (isset($_SESSION['tpl_caching'])) {
    define("TEMPLATE_CACHING", true);
} else {
    define("TEMPLATE_CACHING", false);
}
if (TEMPLATE_CACHING) {
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
}
