<?php

define("DB_HOST", "127.0.0.1");

define("DB_NAME", "webapp");

define("DB_USER", "webapp");

define("DB_PASS", "jawidojawoid1293");

if (isset($_SESSION['db_caching']) && $_GET['db_caching'] == 'true') {
    $_SESSION['db_caching'] = true;
}
if (isset($_SESSION['db_caching']) && $_GET['db_caching'] == 'false') {
    $_SESSION['db_caching'] = false;
}
if (isset($_SESSION['db_caching']) && $_SESSION['db_caching'] == true) {
    define("DB_CACHING", true);
} else {
    define("DB_CACHING", false);
}