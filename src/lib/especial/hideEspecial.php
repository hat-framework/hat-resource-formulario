<?php

class hideEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){

    }	
	
	public function filter($name, $array){
		return array();
	}
			
	public function format($dados, &$value){
		return $value;
	}
}