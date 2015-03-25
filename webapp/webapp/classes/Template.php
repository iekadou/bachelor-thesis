<?php

class Template
{
    private $templateDir = "templates/";

    private $leftDelimiter = '{{';
    private $rightDelimiter = '}}';

    private $templateFile = "";

    private $templateName = "";

    private $template = "";

    public function __construct($tpl_dir = "") {
        // Template Ordner
        global $PATH;
        if ( !empty($tpl_dir) ) {
            $this->templateDir = $PATH.$tpl_dir;
        } else {
            $this->templateDir = $PATH.$this->templateDir;
        }
    }

    private function openTemplate($path) {
        ob_start();
        require($path);
        return ob_get_clean();
    }

    public function load($file) {
        $this->templateName = $file;
        $this->templateFile = $this->templateDir.$file;
        if(!empty($this->templateName) ) {
            if( file_exists($this->templateFile) ) {
                $this->template = $this->openTemplate($this->templateFile);
            } else {
                return false;
            }
        } else {
            return false;
        }
        return $this;
    }

    public function assignVariables() {
        $this->template = preg_replace_callback(
            '/'.$this->leftDelimiter.'(\w+)'.$this->rightDelimiter.'/i',
            function ($matches) {
                if (isset($GLOBALS[$matches[1]])) {
                    return $GLOBALS[$matches[1]];
                }
                return View::get_var($matches[1]);
            },
            $this->template
        );
    }

    public function prepare() {
        $this->assignVariables();
        return $this;
    }

    public function display() {
        echo $this->template;
        return $this;
    }
}
