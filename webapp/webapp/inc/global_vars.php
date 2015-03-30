<?php
require_once($PATH."classes/Globals.php");

// Random Tag
if (View::get_template_var('pjaxr_matching') == 0) {
    if (!isset($DB_CONNECTOR)) {
        $DB_CONNECTOR = new DBConnector();
    }
    $rand_query = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time ORDER BY RAND() LIMIT 1");
    $tag = $rand_query->fetch_array();
    Globals::set_var('tag', $tag['tag']);
    $count_query = $DB_CONNECTOR->query("SELECT Count(*) as cnt FROM tas_delicious_time WHERE tag = '" . $tag . "'");
    if ($count_query->num_rows == 1) {
        $cnt = $count_query->fetch_array();
        Globals::set_var('tag_count', $cnt['cnt']);
    }
}

Globals::set_var('version', "1.0.0a");
