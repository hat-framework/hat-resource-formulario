<?php

class equaltoEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        $arm = armazem::getInstanceOf();
        die($arm->getVar($campo));
        return true;
    }
    
    public function js($campo, $array, $form){

        $name  = $array['name'];
        $equal = GetPlainName($array['equalto'], false);
        $dado  = $form->getDado($equal);
//echo("($equal)");
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addValidation($campo, "equalTo:'#$equal'", "equalTo:'os campos <b>$name</b> e <b>$equal</b> devem ser iguais'");
        
        if(array_key_exists("especial", $dado) && $dado['especial'] != 'equalto'){
            $class = $dado['especial'] . "Especial";
            $file  = dirname(__FILE__) . "/".$class . ".php";
            if(file_exists($file)){
                require_once $file;
                if(class_exists($class)){
                    $obj = new $class($equal, $dado, $form);
                    $obj->Js($campo, $array, $form);
                    return;
                }
            }
        }
        
        $type = $dado['type'];
        $form->$type($campo, $array['name'], @$array['default'], @$array['description']);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>