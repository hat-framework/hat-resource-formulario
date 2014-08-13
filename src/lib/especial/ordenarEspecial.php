<?php

class ordenarEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $value = (array_key_exists('default', $array))?$array['default']:"0";
        $form->hidden($campo, $value);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>