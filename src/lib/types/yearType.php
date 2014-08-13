<?php

class yearType extends typeInterface{
    
    public function validate($campo, $valor){
        if(!$bool = checkdate("12", "12", $valor)) $this->setErrorMessage ("Ano inválido");
        return $bool;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $this->form->text($name, $caption, $value, $desc);
    }
    
    public function getSearchData(){
        
    }
}

?>
