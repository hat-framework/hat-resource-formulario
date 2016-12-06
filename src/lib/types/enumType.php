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
        if(is_array($temp)){$temp = array_shift ($temp);}
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
	
	public function filter($name, $array){
		$out = array();
		$out[$name] = array(
			'name'	  => $array['name'],
			'type'    => 'enum',
			'especial'=>'multi_enum',
			'default' => isset($array['default'])?$array['default']:"",
			'options' => $array['options'],
			'question'=> true
		);
		return $out;
	}
	
	public function genQuery($name, $array, $params){
		if(!isset($params[$name])){return;}
		if(!is_array($params[$name])){$params[$name] = array($params[$name]);}
		foreach($params[$name] as $i => $val){
			if(!isset($array['options'][$val])){safeUnset($val, $params[$name][$i]);}
		}
		if(empty($params[$name])){return;}
		$in = implode("','", $params[$name]);
		return "$name IN ('$in')";
	}
	
	public function format($dados, &$value){
		if(!array_key_exists('options', $dados) || !array_key_exists($value, $dados['options'])){
			return $value;
		}
		$value = $dados['options'][$value];
		return $value;
	}
}