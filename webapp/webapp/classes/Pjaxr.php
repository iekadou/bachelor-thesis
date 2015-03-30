<?php

class Pjaxr
{
    private static $enabled = false;
    private static $previous_namespace = '';
    private static $current_namespace = '';

    public function __construct()
    {
        if (isset($_SERVER['HTTP_X_PJAX_NAMESPACE'])) {
            self::$previous_namespace = $_SERVER['HTTP_X_PJAX_NAMESPACE'];
        } else {
            self::$previous_namespace = '';
        }
        if (isset($_SERVER['HTTP_X_PJAX']) && $_SERVER['HTTP_X_PJAX'] == 'true') {
            self::$enabled = true;
        } else {
            self::$enabled = false;
        }
    }

    public static function is_enabled() {
        return self::$enabled;
    }

    public static function set_current_namespace($namespace) {
        self::$current_namespace = $namespace;
    }

    public static function get_current_namespace() {
        return self::$current_namespace;
    }

    public static function get_matching($extension_namespace = null) {
        if (!self::$enabled) return 0;
        if (!isset($extension_namespace)) {
            $extension_namespace = self::$current_namespace;
        }
        $matching = 0;
        $previous_namespaces = preg_split('/\./i', self::$previous_namespace);
        $extension_namespaces = preg_split('/\./i', $extension_namespace);

        while ($matching < count($extension_namespaces)) {
            if ($previous_namespaces[$matching] == $extension_namespaces[$matching]) {
                $matching++;
            } else {
                break;
            }
        }
        return $matching;
    }

    public static function matches($extension_namespace = null) {
        if (!self::$enabled) return false;
        if (!isset($extension_namespace)) {
            $extension_namespace = self::$current_namespace;
        }
        $previous_namespaces = preg_split('/\./i', self::$previous_namespace);
        $extension_namespaces = preg_split('/\./i', $extension_namespace);
        $extension_namespaces_count = count($extension_namespaces);
        for ($i = 0; $i < $extension_namespaces_count; $i++) {
            if ($previous_namespaces[$i] != $extension_namespaces[$i]) {
                return false;
            }
        }
        return true;
    }
}
new Pjaxr();
