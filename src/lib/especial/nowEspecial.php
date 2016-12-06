<?php

class nowEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = \classes\Classes\timeResource::getFormatedDate();
        return true;
    }
    
    public function js($campo, $array, $form){
       
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