<?php

class ipEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = $_SERVER['REMOTE_ADDR'];
        return true;
    }
    
    public function js($campo, $array, $form){ 
        $form->hidden($campo, $_SERVER['REMOTE_ADDR']);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}