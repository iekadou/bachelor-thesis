<?php

namespace Iekadou\Webapp;

use Iekadou\Pjaxr\Pjaxr as Pjaxr;
use Iekadou\TwigPjaxr\Twig_Pjaxr_TokenParser_PjaxrExtends;
use Twig_Loader_Filesystem;
use Twig_Environment;

class View
{
    static private $id = "";
    static private $name = "";
    static private $account;
    static private $template;
    static private $start_time;
    static private $template_name = '';
    static public $template_vars = array();

    public function __construct($id = '', $name = '', $template = '')
    {
        self::$start_time = microtime(true);

        self::$id = $id;
        self::$name = $name;

        // pjaxr
        Pjaxr::set_current_namespace('Pjaxr.'.$id);
        self::set_template_var('pjaxr_matching', Pjaxr::get_matching());
        self::set_template_var('pjaxr_namespace', Pjaxr::get_current_namespace());

        require_once(PATH."classes/Account.php");

        self::set_template_var('title', SITE_NAME.' - '.$name);

        // account
        self::$account = new Account();

        // template
        if (!empty($template)) {
            self::$template_name = $template;
        } else {
            self::$template_name = $id.'.html';
        }

        $loader = new Twig_Loader_Filesystem(PATH.'templates');
        self::$template = new Twig_Environment($loader);

    }

    public static function get_account() {
        return self::$account;
    }

    public static function get_template() {
        return self::$template;
    }

    public static function set_template_var($var_name, $value) {
        self::$template_vars[$var_name] = $value;
    }

    public static function get_template_var($var_name) {
        if (isset(self::$template_vars[$var_name])) {
            return self::$template_vars[$var_name];
        }
        return false;
    }

    public static function get_template_vars() {
        return self::$template_vars;
    }

    public static function render() {
        global $QUERY_COUNT, $DB_CONNECTION_COUNT;
        self::$template_vars += Globals::get_vars();
        self::set_template_var('query_count', $QUERY_COUNT);
        self::set_template_var('db_connection_count', $DB_CONNECTION_COUNT);
        self::set_template_var('account', self::$account);
        self::$template->addTokenParser(new Twig_Pjaxr_TokenParser_PjaxrExtends());
        self::$template->addTokenParser(new Twig_Url_TokenParser());
        self::$template->addTokenParser(new Twig_Trans_TokenParser());
        self::$template->addTokenParser(new Twig_Time_TokenParser());
        echo self::$template->render(self::$template_name, self::get_template_vars());
    }
}
