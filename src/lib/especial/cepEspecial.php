<?php

class cepEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        if(!is_numeric($valor)){
            $this->setErrorMessage("O campo $campo deve conter apenas números");
            return false;
        }
        if(strlen($valor) != 8){
            $this->setErrorMessage("O campo $campo deve conter 8 dígitos");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){ 
        $this->LoadJsPlugin("formulario/keyfilther", "kf");
        $this->kf->int($campo);
        
        $this->LoadJsPlugin("formulario/cep", 'cep');
        $this->cep->endereco($campo, 'rua', 'bairro', 'cidade', 'estado');
        $form->text($campo, $array['name'], @$array['default'], @$array['description'], "maxlength='8' size='9'");
        
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
    
}

?>