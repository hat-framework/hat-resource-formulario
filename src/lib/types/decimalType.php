<?php

class decimalType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$decimal){
        $decimal = trim($decimal);
        if($decimal == "") {return true;}
        $this->format($campo, $decimal);
        if(!is_numeric($decimal)){
            $this->setErrorMessage("A variável $campo não é um número!");
            return false;
        }
        
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $this->LoadJsPlugin("formulario/keyfilther", "kf");
        $this->kf->decimal($name);
        $extra = "";
        if(isset($array['size'])){
            $mlength = explode(",", $array['size']);
            $length = $mlength[0] + $mlength[1] + 1;
            $extra = "maxlength='{$length}'";
        }
        $this->form->text($name, $caption, $value, $desc, $extra);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
    
    public function format($campo, &$decimal){
        $result = (float)number_format($decimal, 2, '.', '');
        return $result;
    }
}