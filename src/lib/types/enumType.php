<?php

class enumType extends typeInterface{
    
    public $form_type = "select";
    public function validate($campo, &$valor){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $desc = ""){
        $desc     = (isset($array['description']))?$array['description']:"";
        $temp     = $this->form->getVar("$name");
        $selected = "";
        if(is_array($temp)){
            /*if(count($temp) > 1) {
                die("Erro inesperado na classe: ".__METHOD__ . " - ". __LINE__);
            }
            else $temp = array_shift ($temp);*/
            $temp = array_shift ($temp);
        }
        if(array_key_exists("$temp",  $array['options']))$selected = $temp;
        if($selected == "") $selected = array_key_exists('default', $array) ? $array['default'] : '';
        if(!array_key_exists($selected, $array['options']))$selected = "";
        if($selected == "" && @$array['notnull'] === true){
            foreach($array['options'] as $nm => $val){
                $selected = $nm;
                break;
            }
        }
        $arr    = $array['options'];
        $count  = count($arr);
        $qust   = (array_key_exists('question', $array) && $array['question'] == true);
        if(!array_key_exists("multi", $array) || $array['multi'] == false){
            if($count > 4 && !$qust){
                if(@$array['notnull'] !== true) array_unshift($arr, "Selecione uma opção");
                $this->form->select($name, $arr, $selected, $caption, $desc);
            }
            else {
                $lqust = !(array_key_exists('linequestion', $array) && $array['linequestion'] == true);
                $this->form->radioCamp($name, $arr, $selected, $caption, $desc,  "", $lqust);
            }
        }else {
            $selected = $this->form->getVar("$name");
            $this->form->checkCamp($name, $arr, $selected, $caption, $desc);
        }
        
    }
    
    public function getSearchData(){
        die('dasf');
    }
}