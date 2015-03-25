<?php

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
        global $PATH;

        self::$id = $id;
        self::$name = $name;

        // pjaxr
        require_once($PATH."classes/Pjaxr.php");
        $Pjaxr = new Pjaxr();
        $Pjaxr->set_current_namespace('Pjaxr.'.$id);

        require_once($PATH."classes/Account.php");

        self::$template_vars['pjaxr'] = $Pjaxr;
        self::$template_vars['title'] = SITE_NAME.' - '.$name;

        // account
        self::$account = new Account();

        // template
        require_once($PATH."classes/Template.php");
        self::$template = new Template();
        if (!empty($template)) {
            self::$template_name = $template;
        } else {
            self::$template_name = 'endpoints/'.$id.'.php';
        }

    }

    public static function get_account() {
        return self::$account;
    }

    public static function get_template() {
        return self::$template;
    }

    public static function get_var($var_name) {
        if (isset(self::$template_vars[$var_name])) {
            return self::$template_vars[$var_name];
        }
        return false;
    }

    public static function render() {
        self::$template->load(self::$template_name);
        self::$template_vars['render_time'] = round((microtime(true) - self::$start_time), 4, PHP_ROUND_HALF_UP);
        self::$template->prepare()->display();
    }
}
