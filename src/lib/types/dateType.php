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
	//$this->LoadJsPlugin("formulario/calendar", "jqcal");
        //$this->jqcal->draw($name);
        $value = (($value != "")?$value:(isset($array['default'])?$array['default']:""));
        $this->form->text($name, $caption, $value, $desc, 'min="1900-01-01"', 'date');
    }
    
    
    public function getSearchData(){
        die(__CLASS__);
    }
    
}