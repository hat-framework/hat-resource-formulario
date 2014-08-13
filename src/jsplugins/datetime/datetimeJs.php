<?php

use classes\Classes\JsPlugin;
class datetimeJs extends JsPlugin{
    
    public $file_sample = '';
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance))self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init() {
        $this->Html->LoadJs("$this->url/time_picker");
        $this->Html->LoadJs("$this->url/jquery-ui-timepicker-addon");
    }
    
    public function datetime($camp){
        if($camp == ""){throw new \classes\Exceptions\resourceException(__CLASS__, "Campo não pode ser uma string vazia");}
        $function = "$('#$camp').datetimepicker({
            autoPopUp: 'both', buttonImageOnly: true, buttonText: 'Calendar',
            addSliderAccess: true,
            sliderAccessArgs: { touchonly: false },
            hourGrid: 4,
            minuteGrid: 10
         });";
        $this->Html->LoadJQueryFunction($function);
    }
    
    public function time($camp){
        if($camp == ""){throw new \classes\Exceptions\resourceException(__CLASS__, "Campo não pode ser uma string vazia");}
        $this->Html->LoadJQueryFunction(
        "$('#$camp').timePicker({
            addSliderAccess: true,
            sliderAccessArgs: { touchonly: false },
            hourGrid: 4,
            minuteGrid: 10
        });");
        
    }
    
}

?>
