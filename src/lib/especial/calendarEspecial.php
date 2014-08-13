<?php

class calendarEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        
        if(trim($valor) == "") return true;
        
        $arm   = armazem::getInstanceOf();
        $arr   = $arm->getVar($campo);
        if(!is_array($arr)){
            $v = false; 
            foreach(array('datetime', 'date', 'time') as $type){
                if($this->valida($type, $campo, $valor)) {
                    $v = true; 
                    break;
                }
            }
            if($v == false) return false;
        }
        else {
            if(!$this->valida($arr['type'], $campo, $valor)) return false;
            if(!$this->validateMinDate($campo, $valor, $arr['type'])) return false;
        }
        return true;
    }
    
    private function valida($type, $campo, $valor){
        $class = "{$type}Type";
        require_once dirname(__FILE__). "/../types/$class.php";
        $obj = new $class();
        if(!$obj->validate($campo, $valor)){
            $this->setErrorMessage($obj->getErrorMessage());
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        
        $date = $form->getVar($campo);
        $mindate = isset( $array['mindate'])? $array['mindate']:"";
        $maxdate = isset( $array['maxdate'])? $array['maxdate']:"";
        if($date != ""){
            $dt = $date;
            $data = convertData($date);
            $form->setVars($campo, $data, true);
            //if($mindate != "" && $dt != "0000-00-00" && $dt != "0000-00-00 00:00:00"){
            //    $mindate = $dt;
            //}
            
        }elseif(isset($_POST[$campo])) $date = $_POST[$campo];
        $array['type'] = isset($array['type'])?$array['type']:"date";
        //$this->LoadJsPlugin("formulario/calendar", 'calendar');
        $this->LoadJsPlugin("formulario/mobiscroll", 'calendar');
        
        
        $type = "";
        $this->calendar->setEvents($campo, $mindate, $maxdate);
        switch ($array['type']){
            case 'timestamp':
            case 'datetime':
                $this->calendar->datetime($campo);
                break;
            case 'time':
                //if(!MOBILE) 
                $this->calendar->time($campo);
                //else $type = "time";
                break;
            default :
                //if(!MOBILE) 
                $this->calendar->draw($campo);
                //else  $type = "date";
        }
        $this->calendar->clearEvents();
        $form->text($campo, $array['name'], '', @$array['description'], "", $type);
    }
    
    private function validateMinDate($campo, $valor, $type){
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
        }else $mindate = $valor;

        if($this->valida($type, $campo, $mindate)){
            if(\classes\Classes\timeResource::diffDate($valor, $mindate) > 0){
                $this->setErrorMessage("O valor do campo $campsrc deve ser maior ou igual a $mindate");
                return false;
            }
            return true;
        }

        $dados2  = armazem::getInstanceOf()->getVar($mindate);
        $campdst = $dados2['name'];
        $post = armazem::getInstanceOf()->getVar('post');
        if(!isset($post[$mindate])) return true;
        
        $datecamp = \classes\Classes\timeResource::getDbDate($post[$mindate]);
        if(\classes\Classes\timeResource::diffDate($valor, $datecamp) > 0){
            $this->setErrorMessage("O valor do campo $campsrc deve ser maior ou igual ao valor do campo $campdst");
            return false;
        }
        return true;
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
    
}

?>