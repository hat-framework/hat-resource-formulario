<?php

class blobType extends typeInterface{
    
    public $form_type = "file";
    public function validate($campo, &$array){
        return true;
    }

    public function formulario($name, $array, $caption = "", $desc = ""){
        $this->form->file($name, $caption, $desc);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
