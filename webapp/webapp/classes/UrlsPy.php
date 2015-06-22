<?php

namespace Iekadou\Webapp;

class UrlsPy {
    private static $patterns = array();
    public function __construct() {
        UrlsPy::$patterns['home'] = '/';
        UrlsPy::$patterns['imprint'] = '/imprint/';
        UrlsPy::$patterns['tags'] = array('/tags/', '/tags/%s/', '/tags/%s/%s/');
    }

    public static function get_url($name, $args=array()) {
        $arg_count = sizeof($args);
        if (isset(UrlsPy::$patterns[$name])) {
            if (is_array(UrlsPy::$patterns[$name])) {
                foreach(UrlsPy::$patterns[$name] as $url) {
                    if (preg_match_all('/%s/', $url, $matches) == $arg_count) {
                        return vsprintf($url, $args);
                    }
                }
            } else {
                if (preg_match_all('/%s/', UrlsPy::$patterns[$name], $matches) == $arg_count) {
                    return vsprintf(UrlsPy::$patterns[$name], $args);
                }
            }
        }
        throw new Exception(vsprintf("Url '%s' not found with %s args!", array($name, sizeof($args))));
    }
}

new UrlsPy();