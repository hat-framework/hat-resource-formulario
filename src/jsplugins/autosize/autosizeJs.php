<?php

use classes\Classes\JsPlugin;
class autosizeJs extends JsPlugin{
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init(){
        $this->Html->LoadJQuery();
        $this->Html->LoadBowerComponent('jquery-autosize/jquery.autosize.min');
        $this->Html->LoadJQueryFunction("$('textarea').autosize();");
    }    
}