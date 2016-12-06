<?php

class multi_enumEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = serialize($valor);
        return true;
    }
    
    public function js($campo, $array, $form){
        $selected = array_key_exists('default', $array) ? $array['default'] : '';
        $arr      = $array['options'];
        $form->checkCamp($campo, $arr, $selected, $array['name'], @$array['description']);
    }	
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "");
	}
			
	public function format($dados, &$value){
		return $value;
	}
}