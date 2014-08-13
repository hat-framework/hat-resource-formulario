<?php

class rangeEspecial extends especialInterface{
    
    public function __construct() {
        $this->js_validator = GenJsValidatorHelper::getInstanceOf();
    }
    
    public function validate($campo, &$valor){
        
        $arm = armazem::getInstanceOf();
        $array = $arm->getVar($campo);
        if(!array_key_exists('range', $array)) return;
        $array = $array['range'];
        
        $functions = array('checkexat', 'checkmin', 'checkmax');
        foreach($functions as $func)
            if(!$this->$func($array, $valor))return false;
        
        return true;
    }
    
    public function js($campo, $array, $form){
        if(!array_key_exists('range', $array)) return;
        $tarr  = $array;
        $array = $array['range'];
        $extras = "";
        if(array_key_exists("minlenght", $array)){
            $valor      = $array["minlenght"];
            $validation = "minlength: $valor";
            $message    = "minlength: 'Digite pelo menos $valor caracteres'";
            $this->js_validator->addValidation($campo, $validation, $message);
        }
        
        if(array_key_exists("maxlength", $array)){
            $valor      = $array["maxlength"];
            $validation = "maxlength: $valor";
            $message    = "maxlength: 'Digite no máximo $valor caracteres'";
            $this->js_validator->addValidation($campo, $validation, $message);
            $extras = "maxlength = '$valor'";
        }
        $caption = $tarr["name"];
        $value   = "";
        $form->text($campo, $caption, $value, @$array['description'], $extras);
    }
    
    private function checkexat($array, $valor){
        if(!array_key_exists("minlength", $array)) return true;
        if(!array_key_exists("maxlength", $array)) return true;
        
        $min = $array["minlength"];
        $max = $array["maxlength"];
        if($min != $max) return true;
        
        if(is_numeric($min) && strlen($valor) != $min){
            $this->setErrorMessage("O campo deve conter $min caracteres");
            return false;
        }
        
        return true;
    }
    
    private function checkmin($array, $valor){
        if(!array_key_exists("minlength", $array)) return true;
        $min = $array["minlength"];
        if(is_numeric($min) && strlen($valor) <= $min){
            $this->setErrorMessage("O campo deve conter no mínimo $min caracteres");
            return false;
        }
        return true;
    }
    
    private function checkmax($max, $valor){
        if(!array_key_exists("maxlength", $array)) return true;
        $max = $array["maxlength"];
        if(is_numeric($max) && strlen($valor) >= $max){
            $this->setErrorMessage("O campo deve conter no máximo $max caracteres");
            return false;
        }
        return true;
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>