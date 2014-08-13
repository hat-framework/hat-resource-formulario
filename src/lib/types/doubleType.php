<?php

class doubleType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, $array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $this->form->text($name, $caption, $value, $desc);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
