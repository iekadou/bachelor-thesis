<?php

namespace Iekadou\Webapp;

use Iekadou\Lare\Lare as Lare;
use Iekadou\TwigLare\Twig_Lare_Extension;
use Twig_Loader_Filesystem;
use Twig_Environment;

class View
{
    static private $id = "";
    static private $name = "";
    static private $template;
    static private $start_time;
    static private $template_name = '';
    static public $template_vars = array();

    public function __construct($id = '', $name = '', $template = '')
    {
        self::$start_time = microtime(true);

        self::$id = $id;
        self::$name = $name;

        // Lare
        Lare::set_current_namespace('Lare.'.$id);
        self::set_template_var('lare_matching', Lare::get_matching_count());
        self::set_template_var('lare_current_namespace', Lare::get_current_namespace());
        self::set_template_var('title', SITE_NAME.' - '.$name);

        // template
        if (!empty($template)) {
            self::$template_name = $template;
        } else {
            self::$template_name = $id.'.html';
        }

        $loader = new Twig_Loader_Filesystem(PATH.'templates');
        if (TEMPLATE_CACHING) {
            self::$template = new Twig_Environment($loader, array(
                'cache' => PATH.'cached_templates',
            ));
        } else {
            self::$template = new Twig_Environment($loader, array());
        }
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
        self::$template_vars += Globals::get_vars();
        self::set_template_var('QUERY_COUNT', Globals::get_var('QUERY_COUNT'));
        self::set_template_var('DB_CONNECTION_COUNT', Globals::get_var('DB_CONNECTION_COUNT'));
        self::$template->addExtension(new Twig_Lare_Extension());
        self::$template->addTokenParser(new Twig_Url_TokenParser());
        self::$template->addTokenParser(new Twig_Trans_TokenParser());
        self::$template->addTokenParser(new Twig_Time_TokenParser());
        echo self::$template->render(self::$template_name, self::get_template_vars());
    }
}
