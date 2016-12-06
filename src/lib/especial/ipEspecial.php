<?php

class ipEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = $_SERVER['REMOTE_ADDR'];
        return true;
    }
    
    public function js($campo, $array, $form){ 
        $form->hidden($campo, $_SERVER['REMOTE_ADDR']);
    }	
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "IP");
	}
			
	public function format($dados, &$value){
		return $value;
	}
}