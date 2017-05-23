<?php

class fieldsetAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $class = isset($array['fieldsetClass'])?$array['fieldsetClass']:"";
        $form->fieldset("fieldset_$name", $type, $class);
    }
    
    public function validar($name, $type, &$array){
        return true;
    }
    
	public function filter($name, $array){}
	
    public function flush() {
        
    }
}