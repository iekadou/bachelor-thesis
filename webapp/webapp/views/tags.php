<?php
include("../inc/path.php");
require_once($PATH."inc/include.php");

if (!isset($DB_CONNECTOR)) {
    $DB_CONNECTOR = new DBConnector();
}
// only 1 character, '-' or '0-9' is valid
if (isset($_GET['current_char']) && preg_match('/^([a-z-]{1}|(0-9){1})$/', $_GET['current_char'])) {
    $current_char = $DB_CONNECTOR->real_escape_string($_GET['current_char']);
} else { $current_char = 'p'; }

if (isset($_GET['page']) && preg_match('/^[0-9]+$/', $_GET['page'])) {
    $current_page = $_GET['page'];
} else { $current_page = '1'; }

new View('Tags.'.$current_char, getTranslation('Tags'), 'endpoints/tags.php');

View::$template_vars['current_char'] = $current_char;
// pagination
if ($current_char == '-') {
    $tag_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag REGEXP '^[^0-9A-Za-z]';")->fetch_array()['cnt'];
} elseif ($current_char == '0-9') {
    $tag_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag REGEXP '^[0-9]';")->fetch_array()['cnt'];
} else {
    $tag_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag LIKE '".$current_char."%';")->fetch_array()['cnt'];
}

require_once($PATH."classes/Pagination.php");

$pagination = new Pagination($page=$current_page, $obj_count=$tag_count, $page_size=30, $page_offset=1, $url='/tags/'.$current_char.'/%s/');

View::$template_vars['pagination'] = $pagination;

if ($current_char == '-') {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time WHERE tag REGEXP '^[^0-9A-Za-z]' GROUP BY tag LIMIT ".$DB_CONNECTOR->real_escape_string($pagination->get_query_offset()).", ".$DB_CONNECTOR->real_escape_string($pagination->get_page_size()).";");
} elseif ($current_char == '0-9') {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time WHERE tag REGEXP '^[0-9]' GROUP BY tag LIMIT ".$DB_CONNECTOR->real_escape_string($pagination->get_query_offset()).", ".$DB_CONNECTOR->real_escape_string($pagination->get_page_size()).";");
} else {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time WHERE tag LIKE '".$current_char."%' GROUP BY tag LIMIT ".$DB_CONNECTOR->real_escape_string($pagination->get_query_offset()).", ".$DB_CONNECTOR->real_escape_string($pagination->get_page_size()).";");
}
$objects = array();
while ($tag = $queryset->fetch_object()) {
    array_push($objects, $tag);
}

View::$template_vars['objects'] = $objects;



View::render();
