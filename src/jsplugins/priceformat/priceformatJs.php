<?php

use classes\Classes\JsPlugin;
class priceformatJs extends JsPlugin{
    
    public $file_sample = "sample.php";
    private $class      = "monetary";
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name($plugin);
        }

        return self::$instance;
    }
    
    public function init(){
        $this->Html->LoadJQuery();
        $this->Html->LoadBowerComponent("Jquery-Price-Format/jquery.price_format.min");
    }
    
    public function getClass(){
        return $this->class;
    }
    
    public function load($name){
        static $loaded = false;
        if(!$loaded){
            /*$this->Html->LoadJQueryFunction("$('.$this->class').each(function(){
                $(this).number( true, 2, ',','.' );
            });");*/
            $loaded = true;
            $this->Html->LoadJQueryFunction("$('.$this->class').each(function(){
                $(this).priceFormat({ prefix: 'R$ ',centsSeparator:',',clearPrefix: true, thousandsSeparator: '.'});
            });
            ");
        }
        /*
        if($name != ""){
            $this->Html->LoadJQueryFunction("$('#$name').priceFormat({
                    prefix: 'R$ ',centsSeparator:',',clearPrefix: true, thousandsSeparator: '.'});");
        }*/
        
    }
    
}