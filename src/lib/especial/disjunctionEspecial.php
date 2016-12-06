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
	
	public function filter($name, $array){
		echoBr(__METHOD__);
		print_rh($name);
		print_rh($array);
	}
	
	public function format($dados, &$value){
		echoBr(__METHOD__);
		print_rh($value);
		print_rh($dados);
	}
}
