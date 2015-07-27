<?php
    define('SITE_NAME', 'Lare.io');
    define("DB_DEBUG", false);
    define("DB_PROFILER", true);
    if (isset($_GET['db_caching']) && $_GET['db_caching'] == 'true') {
        $_SESSION['db_caching'] = true;
    }
    if (isset($_GET['db_caching']) && $_GET['db_caching'] == 'false') {
        $_SESSION['db_caching'] = false;
    }
    if (isset($_SESSION['db_caching']) && $_SESSION['db_caching'] == true) {
        define("DB_CACHING", true);
    } else {
        define("DB_CACHING", false);
    }
    if (isset($_GET['tpl_caching']) && $_GET['tpl_caching'] == 'true') {
        $_SESSION['tpl_caching'] = true;
    }
    if (isset($_GET['tpl_caching']) && $_GET['tpl_caching'] == 'false') {
    $_SESSION['tpl_caching'] = false;
}
    if (isset($_SESSION['tpl_caching']) && $_SESSION['tpl_caching'] == true) {
        define("TEMPLATE_CACHING", true);
    } else {
        define("TEMPLATE_CACHING", false);
    }

    define("DISPLAY_DEBUG_INFORMATION", false);
    define("DISPLAY_CURRENT_TIME", false);
