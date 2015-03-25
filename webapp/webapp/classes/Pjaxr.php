<?php

class Pjaxr
{
    private $matching = 0;
    private $enabled = false;
    private $previous_namespace = '';
    private $current_namespace = '';
    private $additional_content = '';

    public function __construct()
    {
        if (isset($_SERVER['HTTP_X_PJAX_NAMESPACE'])) {
            $this->previous_namespace = $_SERVER['HTTP_X_PJAX_NAMESPACE'];
        } else {
            $this->previous_namespace = '';
        }
        if (isset($_SERVER['HTTP_X_PJAX'])) {
            $this->enabled = $_SERVER['HTTP_X_PJAX'];
        } else {
            $this->enabled = '';
        }
    }

    public function add_additional_content($content) {
        $this->additional_content .= $content;
    }

    public function set_current_namespace($namespace) {
        $this->current_namespace = $namespace;
        if ($this->enabled == 'true') {
            $previous_namespaces = preg_split('/\./i', $this->previous_namespace);
            $current_namespaces = preg_split('/\./i', $this->current_namespace);

            while ($this->matching < count($current_namespaces)) {
                if ($previous_namespaces[$this->matching] == $current_namespaces[$this->matching]) {
                    $this->matching++;
                } else {
                    break;
                }
            }
        }
    }

    public function get_current_namespace() {
        return $this->current_namespace;
    }

    public function get_matching() {
        return $this->matching;
    }
}
