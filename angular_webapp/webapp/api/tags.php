<?php
require_once("../inc/include.php");

use Iekadou\Webapp\DBConnector as DBConnector;

if (!isset($DB_CONNECTOR)) {
    $DB_CONNECTOR = new DBConnector();
}
// only 1 character, '-' or '0-9' is valid
if (isset($_GET['current_char']) && preg_match('/^([a-z-]{1}|(0-9){1})$/', $_GET['current_char'])) {
    $current_char = $DB_CONNECTOR->real_escape_string($_GET['current_char']);
} else { $current_char = 'all'; }

if (isset($_GET['page']) && preg_match('/^[0-9]+$/', $_GET['page'])) {
    $current_page = $_GET['page'];
} else { $current_page = '1'; }

if (isset($_GET['all_chars'])) {
    // get all tag counts, per starting chars
    $chars = array('-', '0-9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'all');
} else {
    $chars = array($current_char);
}

$char_counts = array();
for ($i=0; $i < sizeof($chars); $i++) {
    $char = $chars[$i];
    if ($char == '-') {
        $char_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag REGEXP '^[^0-9A-Za-z]';");
    } elseif ($char == '0-9') {
        $char_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag REGEXP '^[0-9]';");
    } elseif ($char == 'all') {
        $char_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time;");
    } else {
        $char_count = $DB_CONNECTOR->query("SELECT count(DISTINCT tag) as cnt FROM tas_delicious_time WHERE tag LIKE '".$char."%';");
    }
    $char_count = $char_count->fetch_array();
    $char_counts[$char] = $char_count['cnt'];
}

if ($current_char == '-') {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time WHERE tag REGEXP '^[^0-9A-Za-z]' GROUP BY tag LIMIT ".(($current_page-1)*30).", 30");
} elseif ($current_char == 'all') {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time GROUP BY tag LIMIT ".(($current_page-1)*30).", 30");
} elseif ($current_char == '0-9') {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time WHERE tag REGEXP '^[0-9]' GROUP BY tag LIMIT ".(($current_page-1)*30).", 30");
} else {
    $queryset = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time WHERE tag LIKE '".$current_char."%' GROUP BY tag LIMIT ".(($current_page-1)*30).", 30");
}

for ($objects = array(); $tmp = $queryset->fetch_array(MYSQLI_ASSOC);) $objects[] = $tmp;
$char_list = array();
for ($i = 0; $i<sizeof($chars); $i++) {
    array_push($char_list, array("char" => $chars[$i], "char_count" => $char_counts[$chars[$i]]));
}
echo '{"tags": '.json_encode($objects).', "chars": '.json_encode($char_list).', "page_count": "'.ceil($char_counts[$current_char]/30).'"}';
