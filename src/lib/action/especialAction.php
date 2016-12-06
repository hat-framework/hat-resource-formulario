<?php

class especialAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $class = $type."Especial";
        loadFormFile("lib/especial/$class.php");
        if(!class_exists($class)){return;}
        $obj = new $class();
        $obj->js($name, $array, $form);
    }
    
    public function validar($name, $type, &$array){
        $class = $type."Especial";
        loadFormFile("lib/especial/$class.php");
        if(!class_exists($class)){return false;}

        $obj = new $class();
        if($obj->validate($name, $array) === false){
            $this->setErrorMessage($obj->getErrorMessage());
            return false;
        }
        return true;
    }
	
	public function filter($name, $array){
		if(in_array($array['especial'], array('hide', 'hidden'))){return;}
        $class = $array['especial']."Especial";
        loadFormFile("lib/especial/$class.php");
        if(!class_exists($class)){return;}
        $obj = new $class();
        return $obj->filter($name, $array);
	}
	
	public function format($dados, &$value){
		if(in_array($dados['especial'], array('hide', 'hidden'))){return;}
        $class = $dados['especial']."Especial";
        loadFormFile("lib/especial/$class.php");
        if(!class_exists($class)){return;}
        $obj = new $class();
        return $obj->format($dados, $value);
	}
    
    public function flush() {
        
    }
}