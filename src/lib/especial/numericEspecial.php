<?php

class numericEspecial extends especialInterface{
    
    public function validate($campo, &$value){
        $value = ($value == "FUNC_NULL")?"":$value;
        if($value == "") return true;
        if(!is_numeric($value)){
            $this->setErrorMessage("Digite apenas números inteiros ('$value')");
            return false;
        }
        return true;
    }
    
     public function js($name, $array, $form){
         $this->LoadJsPlugin("formulario/keyfilther", "kf");
         $this->kf->int($name);
         $this->LoadJsPlugin('jqueryui/jqueryui', 'jui');
         $options = isset($array['numeric'])?$array['numeric']:array();
         $this->jui->spiner("#$name", $options);
         $form->text($name, @$array['name'], @$array['default'], @$array['description']);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}