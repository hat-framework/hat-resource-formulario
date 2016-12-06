<?php

class notnullAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $nm          = $array['name'];
        $validation  = "required: true";
        $message     = "required: 'O campo $nm deve ser preenchido'";
        $this->LoadJsPlugin("formulario/jqueryvalidate", "jsval");
        $this->jsval->addValidation($name, $validation, $message);
    }
    
    public function validar($name, $value, &$valor){
        if(trim($valor) == ""){
            $this->setErrorMessage("O campo $name deve ser preenchido");
            return false;
        }
        return true;
    }	
	
	public function filter($name, $array){}
 
    public function flush() {
        
    }
}