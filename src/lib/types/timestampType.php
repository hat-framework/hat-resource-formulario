<?php

class timestampType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$timestamp){
        if(trim($timestamp) == "" || $timestamp === "FUNC_NULL") {return true;}
        
        $timest = explode(" ", $timestamp);
        $date = @$timest[0];
        $time = @$timest[1];
        if($date != "" && !$this->ValidaData($date)){ 
            $this->setErrorMessage("O campo $campo não é uma data válida! Digite DD/MM/AAAA");
            return false;
        }
        if(!$this->checktime($time)){
            $this->setErrorMessage("O campo $campo não é um Horário válido! Digite HH:MM:SS ou HH:MM");
            return false;
        }
            
        $date = convertData($date);
        $timestamp = ($time != "")?"$date $time":"$date 00:00:00";
        return true;
    }
	
			private function ValidaData($dat){
				if($dat == 'FUNC_NULL') return true;
				$dat = str_replace(" ", "", $dat);
				if($dat == "") return true;
				$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como referência
				if(count($data) < 3){
					$data = explode("/",convertData($dat));
					if(count($data) < 3){
						$this->setErrorMessage('Data inválida!');
						return false;
					}
				}
				$day   = (int)$data[0];
				$month = (int)$data[1];
				$year  = (int)$data[2];

				// verifica se a data é válida!
				return checkdate($month,$day,$year);

			}

			private function checktime(&$time){
			   $t = explode(':',$time);
			   $hour = $minute = $segundo = "00";
			   $hour    = isset($t[0])?$t[0]:"00"; 
			   $minute  = isset($t[1])?$t[1]:"00"; 
			   $segundo = isset($t[2])?$t[2]:"00";

			   $time = "$hour:$minute:$segundo";
			   if (($hour > -1 && $hour < 24) && ($minute > -1 && $minute < 60) && (($segundo > -1 && $segundo < 60)||$segundo == ""))
				   return true;
			   return false;
			} 	
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $value = (trim($value) == "00/00/0000 00:00:00" || trim($value) == "00/00/0000")?"":$value;
        $this->form->text($name, $caption, $value, $desc);
    }
	
	public function format($dados, &$value){
		if($valor == '0000-00-00') {
			$valor = "";
			return $valor;
		}
		$valor = \classes\Classes\timeResource::getFormatedDate($valor);
		return $valor;
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, 'Data e Hora');
	}
}