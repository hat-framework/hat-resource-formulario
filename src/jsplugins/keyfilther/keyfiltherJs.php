<?php

use classes\Classes\JsPlugin;
class keyfiltherJs extends JsPlugin{
    
    public $project_url = '';
    public $file_sample = 'sample.php';
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name($plugin);
        }

        return self::$instance;
    }

    public function init() {
        $this->Html->LoadBowerComponent('jquery.keyfilter/jquery.keyfilter');
    }
    
    public function int($name){
        $this->Html->LoadJQueryFunction("$('#$name').keyfilter(/[\d]/);");	
    }
    
    public function decimal($name){
        $this->Html->LoadJQueryFunction("$('#$name').keyfilter(/[\d\-\.]/);");	
    }
}

?>