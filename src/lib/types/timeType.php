<?php

class timeType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$time){
        if($this->is_validTime($campo, $time)) {
            if($campo == "") return true;
            if(!$this->validateMinDate($campo, $time)) return false;
            return true;
        }
        $this->setErrorMessage("Hora no formato inválido, Digite (HH:MM:SS)");
        return false;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $extra = "";
        $value = (($value != "")?$value:(isset($array['default'])?$array['default']:""));
        $this->form->text($name, $caption, $value, $desc, $extra, 'time');
    }
    
    private function validateMinDate($campo, $valor){
        $dados = armazem::getInstanceOf()->getVar($campo);
        $mindate = isset($dados['mindate'])? $dados['mindate']:"";
        if($mindate == "") return true;
        $campsrc = $dados['name'];
        if(CURRENT_ACTION == 'inserir'){
            if($mindate == "now"){
                if(\classes\Classes\timeResource::diffDate($valor, '', 'Mi') > 0){
                    $this->setErrorMessage("O valor do campo $campsrc deve ser maior do que a data/hora atual");
                    return false;
                }
                return true;
            }
        }//else $mindate = $valor;
        
        if($this->is_validTime($campo, $mindate)){
            if(\classes\Classes\timeResource::diffDate($valor, $mindate) > 0){
                $this->setErrorMessage("O valor do campo $campsrc deve ser maior ou igual a $mindate");
                return false;
            }
            return true;
        }
        
        $dados2  = armazem::getInstanceOf()->getVar($mindate);
        $post = armazem::getInstanceOf()->getVar('post');
        if(!isset($post[$mindate])) return true;
        
        $campdst = $dados2['name'];
        $datecamp = \classes\Classes\timeResource::getDbDate($post[$mindate]);
        if(\classes\Classes\timeResource::diffDate($valor, $datecamp) > 0){
            $this->setErrorMessage("O valor do campo $campsrc deve ser maior ou igual ao valor do campo $campdst");
            return false;
        }
        return true;
    }
    
    private function is_validTime($campo, $time){
        if(trim($time) == "") return true;
        if(strstr($time, ":") !== false) {
            list($hour,$minute) = explode(':',$time);
            if ($hour > -1 && $hour < 24 && $minute > -1 && $minute < 60){
                return true;
            }
        }
        return false;
    }	
	
	public function format($dados, &$value){
		if($value == '00:00:00' || $value == '00:00') {$value = "";}
		return $value;
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, 'Hora');
	}
}
