<?php

abstract class block extends classes\Classes\Object{
    
    public function FormLoadPlugin($nome_plugin, $name){
        $class = $nome_plugin . "Plugin";
        loadFormFile("jsplugins/$nome_plugin/$class.php");
        $this->$name = new $class();
    }
    
    public final function setData($data){
        if(!is_array($data)) throw new \classes\Exceptions\resourceException(__CLASS__, "Dados não foram inicializados");
        $this->data = $data;
    }
    
    public final function setName($name){
        if(!is_string ($name) || $name == "")  throw new \classes\Exceptions\resourceException(__CLASS__, "O nome do tipo está incorreto!");
        $this->name = $name;
    }
    
    public final function setModel($model){
        if(!is_object($model) || !($model instanceof Model)) throw new \classes\Exceptions\resourceException(__CLASS__, "O objeto passado não é um modelo");
        $this->model = $model;
    }
    
    public final function reset(){
        $this->data = array();
        $this->name = "";
        $this->model = null;
    }
	
	protected function getParamName($name, $array){
		if(isset($array['filter']['group']) && $array['filter']['group']){
			return str_replace(array('Type', 'Especial'), '', self::whoAmI());
		}
		return $name;
	}
	
	protected function genRange($name, $params){
		$out = array();
		if(isset($params["{$name}_min"]) && $params["{$name}_min"] != "" && $params["{$name}_min"] != '0.00'){
			$out[] = "$name >= '{$params["{$name}_min"]}'";
		}
		if(isset($params["{$name}_max"]) && $params["{$name}_max"] != "" && $params["{$name}_max"] != '0.00'){
			$out[] = "$name <= '{$params["{$name}_max"]}'";
		}
		if(empty($out)){return "";}
		$temp = implode(" AND ", $out);
		return "($temp)";
	}
	
	protected function genEquals($name, $paramname, $params){
		return "$name LIKE '{$params["$paramname"]}'";
	}

	protected function common_filter($name2, $array, $groupped_title){
		$name = $this->getParamName($name2, $array);
		$title = $this->getTitle($array, $groupped_title);
		if(isset($array['filter']['type'])){
			$type = $array['filter']['type'] . "Filter";
			if(method_exists($this, $type)){return $this->$type($name, $title, $array);}
		}
		return array($name => $this->commonFilter($title, $array));
	}


	protected function getTitle($array, $groupped_title){
		return (isset($array['filter']['group']) && $array['filter']['group'])?$groupped_title:$array['name'];
	}

	protected function rangeFilter($name, $title, $array){
		$out = array(
			"$name" => array(
				'name'     => "$title",
				'type'     => $array['type'],
				'especial' => 'dual',
				'dual'	   => array()
			),
		);
		$out[$name]['dual']["{$name}_min"] = $this->commonFilter('Mínimo', $array);
		$out[$name]['dual']["{$name}_max"] = $this->commonFilter('Máximo', $array);
		return $out;
	}			

	protected function commonFilter($title, $array){
		$array['name'] = $title;
		safeUnset('description', $array);
		if(isset($array['especial']) && in_array($array['especial'], array('hide', 'hidden'))){
			safeUnset('especial', $array);
		}
		return $array;
	}
	
	protected function mask($val, $mask) {
		$maskared = '';
		$k = 0;
		for ($i = 0; $i <= strlen($mask) - 1; $i++) {
			if ($mask[$i] == '#') {
				if (isset($val[$k]))
					$maskared .= $val[$k++];
			}
			else {
				if (isset($mask[$i]))
					$maskared .= $mask[$i];
			}
		}
		return $maskared;
	}

}