<?php

class floatType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$value){

        $value = str_replace(array(" ", "R$", ".", "%"), '', $value);
        $value = str_replace(",", '.', $value);
        if(!is_numeric($value)){
            $this->setErrorMessage("Valor digitado deve conter apenas números vírgula e ponto");
            return false;
        }
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $this->form->text($name, $caption, $value, $desc);
    }
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Decimal");
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
			
	public function format($dados, &$value){
		if(!is_numeric($value) || !isset($dados['size'])) {return $value;}
		$e      = explode(',', $dados['size']);
		$casas  = end($e);
		if($casas == "") {$casas = 2;}
		$valor  = number_format($valor, $casas, ',', '.');
		return $valor;
	}
}
