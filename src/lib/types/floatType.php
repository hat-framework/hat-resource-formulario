<?php

class floatType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$value){

        $value = str_replace(array(" ", "R$", ".", "%"), '', $value);
        $value = str_replace(",", '.', $value);
        if(!is_numeric($value)){
            $this->setErrorMessage("Valor digitado deve conter apenas números vírgula e ponto");
            return false;
        }
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
