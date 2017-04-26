<?php

class intType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$value){
        if($value == "") {
            $value = "FUNC_NULL";
            return true;
        }
        if(!is_numeric($value)){
            $this->setErrorMessage("O campo $campo deve conter apenas números inteiros");
            return false;
        }
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
         $this->LoadJsPlugin("formulario/keyfilther", "kf");
         $this->kf->int($name);
         $extra = (isset($array['size']))?"maxlength='{$array['size']}'":"";
         $this->form->text($name, $caption, $value, $desc, $extra);
    }
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Número");
	}
			
	public function format($dados, &$value){
		return $value;
	}
	
	public function genQuery($name, $array, $params){
		$paramname = $this->getParamName($name, $array);
		if(isset($array['filter']['type']) && $array['filter']['type'] === 'range'){
			if((!isset($params["{$paramname}_min"]) || $params["{$paramname}_min"] == "") && (!isset($params["{$paramname}_max"]) || $params["{$paramname}_max"] == "")){return;}
			return $this->genRange($name, $params);
		}elseif(!isset($params[$paramname]) || $params[$paramname] == ""){return;}
		return $this->genEquals($name, $paramname, $params);
	}
}