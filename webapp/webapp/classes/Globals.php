<?php
class Globals {
    private static $vars = array();

    public static function set_var($name, $value) {
        self::$vars[$name] = $value;
    }
    public static function get_var($name) {
        return self::$vars[$name];
    }
    public static function get_vars() {
        return self::$vars;
    }
}
new Globals();
