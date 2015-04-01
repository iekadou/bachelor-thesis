<?php

namespace Iekadou\Webapp;

class Translation
{
    private static $languageDict = array();
    public function __construct() {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        } else {
            $lang = "en";
        }
        switch ($lang) {
            case "de":
                include(PATH . "inc/language_de.php");
                break;
            case "en":
                include(PATH . "inc/language_en.php");
                break;
            default:
                include(PATH . "inc/language_en.php");
                break;
        }
    }

    public static function translate($string)
    {
        if (isset(Translation::$languageDict[$string])) {
            return Translation::$languageDict[$string];
        } else {
            return $string;
        }
    }
}
new Translation();
