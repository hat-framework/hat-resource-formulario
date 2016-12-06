<?php

class blobType extends typeInterface{
    
    public $form_type = "file";
    public function validate($campo, &$array){
        return true;
    }

    public function formulario($name, $array, $caption = "", $desc = ""){
        $this->form->file($name, $caption, $desc);
    }
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Ano");
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
			
	public function format($dados, &$value){
		return $value;
	}
}
