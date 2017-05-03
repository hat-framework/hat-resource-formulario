<?php

class decimalType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$decimal){
        $decimal = trim($decimal);
        if($decimal == "") {return true;}
        $this->format2($campo, $decimal);
        if(!is_numeric($decimal)){
            $this->setErrorMessage("A variável $campo não é um número!");
            return false;
        }
        
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $this->LoadJsPlugin("formulario/keyfilther", "kf");
        $this->kf->decimal($name);
        $extra = "";
        if(isset($array['size'])){
            $mlength = explode(",", $array['size']);
            $length = $mlength[0] + $mlength[1] + 1;
            $extra = "maxlength='{$length}'";
        }
        $this->form->text($name, $caption, $value, $desc, $extra);
    }
	
    public function format2($campo, &$decimal){
        $result = (float)number_format($decimal, 2, '.', '');
        return $result;
    }
	
    public function filter($name, $array){
		return $this->common_filter($name, $array, "Número");
	}
	
	public function format($dados, &$value){
		if(!is_numeric($value) || !isset($dados['size'])) {return $value;}
		$e      = explode(',', $dados['size']);
		$casas  = end($e);
		if($casas == "") {$casas = 2;}
		$valor  = number_format($valor, $casas, ',', '.');
		return $valor;
	}
	
	public function genQuery($name, $array, $params){
		if(!isset($array['filter'])){return;}
		
		$paramname = $this->getParamName($name, $array);
		if(isset($array['filter']['type']) && $array['filter']['type'] === 'range'){
			if(!isset($params["{$paramname}_min"]) && !isset($params["{$paramname}_max"])){return;}
			if($params["{$paramname}_min"] == "" && $params["{$paramname}_max"] == ""){return;}
			$this->getValid($paramname, $array, $params);
			return $this->genRange($name, $params);
		}elseif(!isset($params[$paramname]) || $params[$paramname] == ""){return;}
		$this->validate2('', $params["$paramname"]);
		return $this->genEquals($name, $paramname, $params);
	}
	
			private function getValid($name, $array, &$params){
				if(isset($params["{$name}_min"])){
					$this->validate2('', $params["{$name}_min"]);
				}
				if(isset($params["{$name}_max"])){
					$this->validate2('', $params["{$name}_max"]);
				}
			}
	
					private function validate2($campo, &$valor){
						$valor = str_replace("R$ ", "", $valor);
						if(false === strstr($valor, ',')){return true;}
						$valor = str_replace(array('.',','), array('','.'), $valor);
						return true;
					}
}