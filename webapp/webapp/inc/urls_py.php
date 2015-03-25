<?php
class UrlsPy {
    private static $patterns = array();
    public function __construct() {
        UrlsPy::$patterns['home'] = '/';
        UrlsPy::$patterns['imprint'] = '/imprint/';
        UrlsPy::$patterns['logout'] = '/logout/';
        UrlsPy::$patterns['register'] = '/register/';
        UrlsPy::$patterns['tags'] = ['/tags/', '/tags/%s/', '/tags/%s/%s/'];

        UrlsPy::$patterns['account:dashboard'] = '/account/';
        UrlsPy::$patterns['account:profile'] = '/account/profile/';

        UrlsPy::$patterns['api:login'] = '/api/login/';
        UrlsPy::$patterns['api:profile'] = '/api/profile/';
        UrlsPy::$patterns['api:register'] = '/api/register/';
    }

    public static function get_url($name, $args=array()) {
        $arg_count = sizeof($args);
        if (isset(UrlsPy::$patterns[$name])) {
            if (is_array(UrlsPy::$patterns[$name])) {
                foreach(UrlsPy::$patterns[$name] as $url) {
                    if (preg_match_all('/%s/', $url) == $arg_count) {
                        return vsprintf($url, $args);
                    }
                }
            } else {
                if (preg_match_all('/%s/', UrlsPy::$patterns[$name]) == $arg_count) {
                    return vsprintf(UrlsPy::$patterns[$name], $args);
                }
            }
        }
        return '/';
    }
}

new UrlsPy();