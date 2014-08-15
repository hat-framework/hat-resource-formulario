<?php

class dinamicEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $class = $array['type'] ."Type";
        $dir   = classes\Classes\Registered::getResourceLocation('formulario', true);
        require_once $dir."lib/types/$class.php";
        
        $value = $form->getVar($campo);
        if($value == "")
            if(array_key_exists("default", $array))
                $value = $array['default'];
        $desc = (array_key_exists("descricao", $array))?$array['descricao']:"";
            
        $obj = new $class();
        $obj->setForm($form);
        $obj->formulario($campo, $array, $array['name'], $value, $desc);
        //seta o campo no formulario
        //$form->hidden($campo, $value);
        
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}