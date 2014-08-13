<?php

class emailEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        if($valor == "") return true;
        if(filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE) {
            $this->setErrorMessage("O campo ($campo) não é um email válido");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        $validation = "email: true";
        $message    = "email: 'Email inválido! '";
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addValidation($campo, $validation, $message);
        $form->text($campo, $array['name'], @$array['default'], @$array['description'], "", 'email');
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>