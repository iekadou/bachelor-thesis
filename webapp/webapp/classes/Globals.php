<?php
namespace Iekadou\Webapp;

use Iekadou\Pjaxr\Pjaxr as Pjaxr;

class Globals {
    private static $vars = array();

    public function __construct() {
    }

    public static function set_var($name, $value) {
        self::$vars[$name] = $value;
    }
    public static function get_var($name) {
        return self::$vars[$name];
    }
    public static function get_vars() {
        if (Pjaxr::get_matching() == 0) {
            if (!isset($DB_CONNECTOR)) {
                $DB_CONNECTOR = new DBConnector();
            }
            $rand_query = $DB_CONNECTOR->query("SELECT tag FROM tas_delicious_time ORDER BY RAND() LIMIT 1");
            $tag = $rand_query->fetch_array();
            $tag = $tag['tag'];
            Globals::set_var('tag', $tag);
            $count_query = $DB_CONNECTOR->query("SELECT Count(*) as cnt FROM tas_delicious_time WHERE tag = '" . $tag . "'");
            if ($count_query->num_rows == 1) {
                $cnt = $count_query->fetch_array();
                Globals::set_var('tag_count', $cnt['cnt']);
            }
        }
        Globals::set_var('PJAXR', PJAXR);
        Globals::set_var('version', "1.0.0a");
        return self::$vars;
    }
}
new Globals();
