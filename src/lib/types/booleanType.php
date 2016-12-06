<?php

class booleanType extends typeInterface{
    
    public $form_type = "";
    public function validate($campo, $array){
        return true;
    }
    
    public function formulario($campo, $array){
    
    }
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Número");
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
			
	public function format($dados, &$value){
		return $value;
	}
}
