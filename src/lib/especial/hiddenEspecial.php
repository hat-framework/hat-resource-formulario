<?php

class hiddenEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $value = (array_key_exists('default', $array))?$array['default']:"";
        $form->hidden($campo, $value);
    }	
	
	public function filter($name, $array){
		return;
	}
	
	public function format($dados, &$value){
		return $value;
	}
}