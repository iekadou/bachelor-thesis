<?php
// Translations
// get user language
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} else {
    $lang = "en";
}

switch ($lang) {
    case "de":
        include($PATH . "inc/language_de.php");
        break;
    case "en":
        include($PATH . "inc/language_en.php");
        break;
    default:
        include($PATH . "inc/language_en.php");
        break;
}

function get_translation($string)
{
    global $languageDict;
    if (isset($languageDict[$string])) {
        return $languageDict[$string];
    } else {
        return $string;
    }
}
