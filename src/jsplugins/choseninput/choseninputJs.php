<?php

use classes\Classes\JsPlugin;
class choseninputJs extends JsPlugin{
        
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init(){
        static $loaded = false;
        if($loaded === true){return;}
        $loaded = true;
        $this->LoadResource('html', 'html')->LoadJquery();
        $this->html->LoadBowerComponent('chosen/chosen.jquery.min', array('chosen/chosen.min'));
        $this->html->LoadJqueryFunction('$(".chosen-select").chosen();');
    }
    
    public function getClass(){
        return "chosen-select";
    }
    
}