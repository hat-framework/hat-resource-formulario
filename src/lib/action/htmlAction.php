<?php

class htmlAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $form->addToForm($array['html']);
    }
    
    public function validar($name, $type, &$array){
        return true;
    }
    
    public function flush() {
        
    }
	
	public function filter($name, $array){
		return ;
	}
}