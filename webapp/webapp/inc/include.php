<?php
define('SITE_NAME', 'Pjaxr.io');
session_start();

if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} else {
    $lang = "en";
}
$REQUEST_METHOD = "GET";
if (isset($_POST['_method']) && ($_POST['_method'] == 'GET' || $_POST['_method'] == 'POST' || $_POST['_method'] == 'PUT' || $_POST['_method'] == 'DELETE')) {
    $REQUEST_METHOD = $_POST['_method'];
}

require_once($PATH."classes/View.php");
require_once($PATH."classes/DBConnector.php");

switch ($lang){
    case "de":
        include($PATH."inc/language_de.php");
        break;
    case "en":
        include($PATH."inc/language_en.php");
        break;        
    default:
        include($PATH."inc/language_en.php");
        break;
}

function getTranslation($string) {
    global $languageDict;
    if (isset($languageDict[$string])) {
        return $languageDict[$string];
    } else return $string;
}


include($PATH."inc/urls_py.php");
function getUrl($url) {
    global $UrlsPy;
    if (isset($UrlsPy[$url])) {
        return $UrlsPy[$url];
    }
    return "/";
}

function include_template($template) {
    global $PATH;
    include($PATH."template/".$template.".php");
}


function raise404() {
    global $title;
    header("HTTP/1.0 404 Not Found");
    $title = SITE_NAME.' - '.getTranslation("not found");
    $tpl = new Template("errors/");
    $tpl->load("404.php")->prepare()->display();
}

function stringify_errors($errors) {
    $error_output = "{";
    foreach ($errors as $error) {
        if ($error_output != "{") {
            $error_output .= ",";
        }
        $error_output .= '"'.$error.'": "error"';
    }
    $error_output .= "}";
    return $error_output;
}

function fixOrientation($img) {
  $exif = @exif_read_data($img);
  if (isset($exif['Orientation'])) {
      $orientation = $exif['Orientation'];
      switch($orientation) {
          case 6: // rotate 90 degrees CW
              $degrees = -90;
          break;
          case 8: // rotate 90 degrees CCW
             $degrees = 90;
          break;

      }
      if (isset($degrees)) {
          $source = imagecreatefromjpeg($img);
          $rotate = imagerotate($source, $degrees, 0);
  
          imagejpeg($rotate, $img);
          imagedestroy($source);
          imagedestroy($rotate);
      }   
  }
}
include($PATH."lib/password.php");
