<?php

class disjunctionEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($name, $array, $form){
        $selected = $array['default'];
        $arr      = $array['options'];
        $caption  = $array['name'];
        $disj     = $array['disjunction'];
        $desc     = array_key_exists('description', $array) ? $array['description']: "";
        $count    = count($arr);
        if($count > 5)
            $form->select($name, $arr, $selected, $caption, $desc);
        else
            $form->radioCamp($name, $arr, $selected, $caption, $desc);
        print_r($disj);
        
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
