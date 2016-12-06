<?php

class datetimeType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$timestamp){
        $timestamp = trim($timestamp);
        if(in_array($timestamp, array("FUNC_NOW()", ''))){return true;}
        $this->formatField($timestamp);
        $matches = array();
        if (!preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $timestamp, $matches)) {
            $this->setErrorMessage("Data ($timestamp) no formato inválido, Digite (DD/MM/AAAA HH:MM:SS) - ". count($matches));
            return false;
        }
        
        if(!checkdate($matches[2], $matches[3], $matches[1])){
            $this->setErrorMessage("Data ($timestamp) no inválida, Digite (DD/MM/AAAA HH:MM:SS) - ". count($matches));
            return false;
        }
        
        return true;
    }
    
	public function formatField(&$timestamp){
		if(strstr($timestamp, "T")) {$timestamp = str_replace("T", " ", $timestamp);}
		$t  = \classes\Classes\timeResource::getDbDate($timestamp);
		$e  = explode(' ', $t);
		while (count($e) > 2){
			array_pop($e);
		}
		$timestamp = implode(" ", $e);
		if(count($e) == 2){
			$e2 = explode(':', $e[1]);
			if(count($e2) == 2){
				$timestamp .= ':00'; 
			}
		}
	}

    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $var = $this->form->getVar($name);
        if($var != ""){
            $this->form->setVar($name, "");
            $value = $var;
        }
        
        $v = ($value == "00/00/0000 00:00:00" || $value == "00/00/0000")?"":$value;
        $value = (($value != "")?$value:(isset($array['default'])?$array['default']:""));
        $e = explode(' ', $value);
        if(count($e) == 2){ 
            $e2 = explode(':', $e[1]);
            if(count($e2) == 3){
                array_pop($e2);
                $value = $e[0]." ".  implode(":", $e2);
            }
        }
        $value = str_replace(" ", "T", $value);
        $this->form->text($name, $caption, $value, $desc, "", "datetime-local");
    }
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, 'Data e Hora');
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
	
	public function format($dados, &$value){
		if($valor == '0000-00-00') {
			$valor = "";
			return $valor;
		}
		$valor = \classes\Classes\timeResource::getFormatedDate($valor);
		return $valor;
	}
	
}