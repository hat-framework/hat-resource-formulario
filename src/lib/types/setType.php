<?php

class setType extends typeInterface{
    
    public $form_type = "";
    public function validate($campo, $array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $temp     = $this->form->getVar($name);
        if(!in_array($temp,  $array['options']))
            $selected = array_key_exists('default', $array) ? $array['default'] : '';
        else {
            foreach($array['options'] as $n => $v){
                if($v == $temp){
                    $selected = "$n";
                    break;
                }
            }
            if($selected == "") $selected = array_key_exists('default', $array) ? $array['default'] : '';

        }
        $arr      = $array['options'];
        $count    = count($arr);
        if(!array_key_exists("multi", $array) || $array['multi'] == false){
            if($count > 5) $this->form->select   ($name, $arr, $selected, $caption, $desc);
            else           $this->form->radioCamp($name, $arr, $selected, $caption, $desc);
        }else{
            $this->form->checkCamp($name, $arr, $selected, $caption, $desc);
        }
    }	
	
	public function filter($name, $array){
		$out = array();
		$out[$name] = array(
			'name'	  => $array['name'],
			'type'    => 'enum',
			'default' => isset($array['default'])?$array['default']:"",
			'options' => $array['options'],
		);
		return $out;
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
	
	public function format($dados, &$value){
		if(!array_key_exists('options', $dados) || !array_key_exists($value, $dados['options'])){
			return $value;
		}
		$value = $dados['options'][$value];
		return $value;
	}
}
