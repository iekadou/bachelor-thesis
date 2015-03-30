<?php

function raise404()
{
    global $PATH;
    header("HTTP/1.0 404 Not Found");
    include($PATH . "views/_errors/404.php");
}

function stringify_errors($errors)
{
    $error_output = "{";
    foreach ($errors as $error) {
        if ($error_output != "{") {
            $error_output .= ",";
        }
        $error_output .= '"' . $error . '": "error"';
    }
    $error_output .= "}";
    return $error_output;
}
