<?php

class Template {
 
    var $template_data = array();
    protected $CI; // Tambahkan baris ini untuk mendefinisikan properti CI

    function __construct() {
        $this->CI =& get_instance(); // Ambil instance CodeIgniter
    }
 
    function set($name, $value)
    {
        $this->template_data[$name] = $value;
    }
 
    function load($template = '', $view = '', $view_data = array(), $return = FALSE)
    {
        $this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
        return $this->CI->load->view($template, $this->template_data, $return);
    }
}
