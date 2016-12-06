<?php

class telefoneEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        if(trim($valor) === "") {return true;}
        $valor = str_replace(array('(', ')', " ", "-", "_"), "", $valor);
        if(!is_numeric($valor)){
            $this->setErrorMessage("O telefone ($valor) não pode conter letras");
            return false;
        }
        elseif(strlen($valor) < 10){
            $this->setErrorMessage("O telefone deve conter 10 números, 
                os dois primeiros o ddd e os 8 últimos o número local");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addMask("$('#$campo').mask('(99) 9999-9999?9');");
        $form->text($campo, $array['name'], @$array['default'], @$array['description'], "autocomplete='off'", 'tel');
    }
	
	public function filter($name, $array){
		return array($name => $this->commonFilter($array['name'], $array));
	}
	
	public function format($dados, &$value){
		$phone = preg_replace("/[^0-9]/", "", $value);
		if(strlen($phone) == 8){
			$value = preg_replace("/([0-9]{4})([0-9]{4})/", "$1-$2", $phone);
		}
		elseif(strlen($phone) >= 10){
			$value = preg_replace("/([0-9]{2})([0-9]{4})([0-9]{4}|[0-9]{5})/", "($1) $2-$3", $phone);
		}
		$value = "<a href=\"tel:$phone\">$value</a>";
		return $value;
	}
}