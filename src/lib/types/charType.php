<?php

class charType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $this->form->text($name, $caption, $value, $desc);
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
