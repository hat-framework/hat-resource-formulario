<?php

class telefoneEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        if(trim($valor) === "") {return true;}
        $valor = str_replace(array('(', ')', " ", "-"), "", $valor);
        if(!is_numeric($valor)){
            $this->setErrorMessage("O telefone ($valor) não pode conter letras");
            return false;
        }
        elseif(strlen($valor) < 10){
            $this->setErrorMessage("O telefone deve conter 10 números, 
                os dois primeiros o ddd e os 8 últimos o número local");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addMask("$('#$campo').mask('(99) 9999-9999?9');");
        $form->text($campo, $array['name'], @$array['default'], @$array['description'], "autocomplete='off'", 'tel');
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}