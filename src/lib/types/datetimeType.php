<?php

class datetimeType extends typeInterface{
    
    public $form_type = "text";
    public function validate($campo, &$timestamp){
        if(trim($timestamp) == "") return true;
        $this->format($timestamp);
        
        $matches = array();
        if (!preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $timestamp, $matches)) {
            $this->setErrorMessage("Data ($timestamp) no formato inválido, Digite (DD/MM/AAAA HH:MM:SS) - ". count($e));
            return false;
        }
        
        if(!checkdate($matches[2], $matches[3], $matches[1])){
            $this->setErrorMessage("Data ($timestamp) no inválida, Digite (DD/MM/AAAA HH:MM:SS) - ". count($e));
            return false;
        }
        
        return true;
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
    
    public function getSearchData(){
        die(__CLASS__);
    }
    
    public function format(&$timestamp){
        if(strstr($timestamp, "T")) $timestamp = str_replace("T", " ", $timestamp);
        $timestamp = \classes\Classes\timeResource::getDbDate($timestamp);
        
        $e = explode(' ', $timestamp);
        if(count($e) == 2){ 
            $e = explode(':', $e[1]);
            if(count($e) == 2){
                $timestamp .= ':00'; 
            }
        }
    }
}

?>
