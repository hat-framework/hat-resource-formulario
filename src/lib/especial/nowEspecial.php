<?php

class nowEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = \classes\Classes\timeResource::getFormatedDate();
        return true;
    }
    
    public function js($campo, $array, $form){
       
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>