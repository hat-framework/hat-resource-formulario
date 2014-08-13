<?php

use classes\Classes\Object;
class JsValidator extends classes\Classes\Object{
    
    protected $js_validator  = NULL;
    private   $instance      = NULL;
    
    public function __construct() {
        $this->js_validator = GenJsValidatorHelper::getInstanceOf();
    }
    
    public static function getInstanceOf()
    {
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name;
        }

        return self::$instance;
    }
}
?>