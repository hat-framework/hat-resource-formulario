<?php

class intType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$value){
        if($value == "") {
            $value = "FUNC_NULL";
            return true;
        }
        if(!is_numeric($value)){
            $this->setErrorMessage("O campo $campo deve conter apenas números inteiros");
            return false;
        }
        return true;
    }
    
     public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
         $this->LoadJsPlugin("formulario/keyfilther", "kf");
         $this->kf->int($name);
         $extra = (isset($array['size']))?"maxlength='{$array['size']}'":"";
         $this->form->text($name, $caption, $value, $desc, $extra);
    }
    
    public function getSearchData(){
        echo $this->name;
        print_r($this->data);
        die(__CLASS__);
    }
}

?>
