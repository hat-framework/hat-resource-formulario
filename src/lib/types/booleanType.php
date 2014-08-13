<?php

class booleanType extends typeInterface{
    
    public $form_type = "";
    public function validate($campo, $array){
        return true;
    }
    
    public function formulario($campo, $array){
    
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
