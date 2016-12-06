<?php

class dateType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$data){
        if(trim($data) == "") return true;
        $data = \classes\Classes\timeResource::getDbDate($data);
        $dt   = explode(" ", $data);
        $data = array_shift($dt);
        if (!preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $data, $matches)) {
            $this->setErrorMessage("Data ($data) no formato inválido, Digite (DD/MM/AAAA)");
            return false;
        }
        
        if(!checkdate($matches[2], $matches[3], $matches[1])){
            $this->setErrorMessage("Data no formato inválido, Digite (DD/MM/AAAA)");
            return false;
        }
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $type = 'date';
        if((strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') === false)){
            $type = 'text';
            $this->LoadJsPlugin("jqueryui/jqueryui", "jqcal")->datepicker($name);
            $var = $this->form->getVar($name);
            if($var != ""){
                $temp = trim(classes\Classes\timeResource::getFormatedDate($var));
                $this->form->setVar($name, $temp);
            }
        }
        $value = (($value != "")?$value:(isset($array['default'])?$array['default']:""));
        $this->form->text($name, $caption, $value, $desc, 'min="1900-01-01" data-type="date"', $type);
    }
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Número");
	}
	
	public function genQuery($name, $array, $params){
		$paramname = $this->getParamName($name, $array);
		if(isset($array['filter']['type']) && $array['filter']['type'] === 'range'){
			if($params["{$paramname}_min"] == "" && $params["{$paramname}_max"] == ""){return;}
			return $this->genRange($name, $params);
		}elseif($params[$paramname] == ""){return;}
		return $this->genEquals($name, $paramname, $params);
	}
			
	public function format($dados, &$value){
		if($value == '0000-00-00') {
			$value = "";
			return $value;
		}
		$value = \classes\Classes\timeResource::getFormatedDate($value);
		return $value;
	}
    
}