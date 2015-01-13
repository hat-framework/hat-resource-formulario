<?php

class monetaryEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = str_replace("R$ ", "", $valor);
        if(false === strstr($valor, ',')){return true;}
        $valor = str_replace(array('.',','), array('','.'), $valor);
        return true;
    }
    
    public function js($campo, $array, $form){
        $this->LoadJsPlugin("formulario/priceformat", "pformat");
        $this->pformat->load($campo);
        $class = $this->pformat->getClass();
        $value = $form->getVar($campo);
        if(trim($value) !== ""){
            $form->setVar($campo, number_format($value, 2, ',', '.'));
        }
        $form->text($campo, $array["name"], @$array['default'], @$array['description'], "class='form-control $class'");
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}