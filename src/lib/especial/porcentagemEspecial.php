<?php

class porcentagemEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = str_replace("%", "", $valor);
        $teste = str_replace(".", "", $valor);
        if(!is_numeric($teste)){
            $this->setErrorMessage("O campo $campo deve conter apenas números");
            return false;
        }
        if($teste > 100){
            $this->setErrorMessage("O campo $campo não pode ultrapassar o valor de 100%");
            return false;
        }
        
        if($teste < 0){
            $this->setErrorMessage("O campo $campo não pode ser menor do que 0%");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        
        $mask    = "$('#$campo').mask('99.9%');";
        $this->LoadJsPlugin("formulario/jqueryvalidate", "jsval");
        $this->jsval->addMask($mask);
        $form->text($campo, $array["name"], @$array['default'], @$array['description']);
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
			$type->format($dados, $value);
			$value = "$value %";
			return $value;
		} catch (Exception $ex) {
			return;
		}
	}
}