<?php

namespace Iekadou\Webapp;

use Iekadou\Lare\Lare as Lare;

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
        if (Lare::get_matching_count() == 0) {
            Globals::set_var('current_time', date('d.m.Y - H:i:s', time()));
        }
        Globals::set_var('Lare', Lare);
        Globals::set_var('version', "1.0.0a");
        return self::$vars;
    }
}
new Globals();
