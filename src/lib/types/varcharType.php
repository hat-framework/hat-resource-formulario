<?php

class varcharType extends typeInterface{
    
    public $form_type = "text";
    
    //qualquer coisa é varchar, não temos que validar
    public function validate($campo, &$array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $size = isset($array['size'])?$array['size']:0;
        if($size >= 200){
            $nm = GetPlainName($name);
            $this->form->textarea($name, $caption, $value, $desc, "onkeyup=\"countChar(this, $size, 'v$nm');\" size =\"$size\"");
            return $this->LoadResource('html', 'html')->LoadJs(URL_JS.'/lib/formulario/countchar');
        }
        $extra = (isset($array['size']))?"maxlength='{$array['size']}'":"";
        $this->form->text($name, $caption, $value, $desc, $extra);
    }
	
	public function filter($name, $array){
		if(!isset($array['filter'])){return;}
		safeUnset('range', $array['filter']);
		return $this->common_filter($name, $array, 'Valor');
	}
	
	public function genQuery($name, $array, $params){
		if(!isset($array['filter'])){return;}
		safeUnset('range', $array['filter']);
		$param_name = $name;
		if(isset($array['filter']['group']) && $array['filter']['group'] === true){
			$param_name = str_replace(array('Type', 'Especial'), '', self::whoAmI());
		}
		if(!isset($params[$param_name]) || $params[$param_name] == ""){return;}
		return $this->getSearchString($name, $params[$param_name], $array);
	}
	
			private function getSearchString($name, $param, $array){
				if(!isset($array['filter']['type'])){
					if(isset($array['pkey']) && $array['pkey']){return "$name LIKE '$param%'";}
					return "$name LIKE '%$param%'";
				}
				$out = str_replace('like', "$param", $array['filter']['type']);
				return "$name LIKE '$out'";
			}
			
	public function format($dados, &$value){
		if(!isset($dados['format']) || !isset($dados['format']['keeptags']) || $dados['format']['keeptags'] != true){
			$value = strip_tags($value, "<b><a><ul><li><ol><i><u>");
		}
		if(strlen($value) <= 120) {return;}
		$value = Resume($value, 120);
		return $value;
	}
}