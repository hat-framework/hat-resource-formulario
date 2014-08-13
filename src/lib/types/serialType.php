<?php

class serialType extends typeInterface{
    
    public $form_type = "";
    public function validate($campo, $array){
        return true;
    }
    
    public function formulario($campo, $array){
        //$this->form->input($campo, ucfirst($campo));
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
