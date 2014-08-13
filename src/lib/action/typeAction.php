<?php

use classes\Classes\Object;
class typeAction extends classes\Classes\Object implements actionInterface{
    
    public $form_type = "";
    public $actions = array();
    
    private function getNewInstance($class, $form = ""){
        
        if(!array_key_exists($class, $this->actions)){
            loadFormFile("lib/types/$class.php");
            if(!class_exists($class))
                return false;
            
            $this->actions[$class] = new $class();
            if($form != "")
                $this->actions[$class]->setForm($form);
            return true;
        }
        return true;
    }
    
    public function executar($name, $type, $array, $form){
        
        if(array_key_exists("especial", $array)){
            return true;
        }
        
        //se não existe o determinado tipo, continua
        $class = $type."Type";
        if(!$this->getNewInstance($class, $form))return true;
        
        //recupera a instancia do novo objeto
        $obj = $this->actions[$class];
        $caption = array_key_exists("name", $array)       ?$array['name']       :$name;
        $desc    = array_key_exists("description", $array)?$array['description']:"";
        $obj->formulario($name, $array, $caption, "", $desc);
    }
    
    public function validar($name, $type, &$post){

        //se não existe o determinado tipo, continua
        $class = $type."Type";
        if(!$this->getNewInstance($class))return true;
        
        //recupera a instancia do novo objeto
        $obj = $this->actions[$class];
        //echo "$name - $post\n";
        //valida o tipo da classe
        if(!$obj->validate($name, $post)){
            $this->setErrorMessage($obj->getErrorMessage());
            return false;
        }
        return true;
    }
    
    public function flush() {
        
    }
}

?>
