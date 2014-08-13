<?php

class binaryType extends typeInterface{
    
    public $type = "file";
    public function validate($campo, $array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $desc = ""){
    	
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
