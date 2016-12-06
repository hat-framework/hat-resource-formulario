<?php

class dinamicEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $class = $array['type'] ."Type";
        $dir   = classes\Classes\Registered::getResourceLocation('formulario', true);
        require_once $dir."/src/lib/types/$class.php";
        
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
	
	public function filter($name, $array){
		if(!isset($array['type'])){return;}
		try{
			$class = "{$array['type']}Type";
			loadFormFile("lib/action/types/$class.php");
			$type = new $class();
			return $type->filter($name, $array);
		} catch (Exception $ex) {
			return;
		}
	}
	
	public function format($dados, &$value){
		if(!isset($dados['type'])){return;}
		try{
			$class = "{$dados['type']}Type";
			loadFormFile("lib/action/types/$class.php");
			$type = new $class();
			return $type->format($dados, $value);
		} catch (Exception $ex) {
			return;
		}
	}
}