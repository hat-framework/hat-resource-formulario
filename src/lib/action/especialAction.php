<?php

use classes\Classes\Object;
class especialAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $class = $type."Especial";
        loadFormFile("lib/especial/$class.php");
        if(!class_exists($class)){return;}
        $obj = new $class();
        $obj->js($name, $array, $form);
    }
    
    public function validar($name, $type, &$array){
        $class = $type."Especial";
        loadFormFile("lib/especial/$class.php");
        if(!class_exists($class)){return false;}

        $obj = new $class();
        if($obj->validate($name, $array) === false){
            $this->setErrorMessage($obj->getErrorMessage());
            return false;
        }
        return true;
    }
    
    public function flush() {
        
    }
}