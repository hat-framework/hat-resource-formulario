<?php

class typeAction extends classes\Classes\Object implements actionInterface{
    
    public $form_type = "";
    public $actions = array();
    
    private function getNewInstance($class, $form = ""){
        if(!array_key_exists($class, $this->actions)){
            loadFormFile("lib/types/$class.php");
			if(!class_exists($class)){return false;}
            
            $this->actions[$class] = new $class();
        }
		if($form != "" && $this->actions[$class]->getForm() == ''){$this->actions[$class]->setForm($form);}
        return true;
    }
    
    public function executar($name, $type, $array, $form){
        
		$array['type'] = $type;
		$obj = $this->getObject($array, $form);
		if(is_bool($obj)){return true;}
		
		
        $caption = array_key_exists("name", $array)       ?$array['name']       :$name;
        $desc    = array_key_exists("description", $array)?$array['description']:"";
        $obj->formulario($name, $array, $caption, "", $desc);
    }
    
    public function validar($name, $type, &$post){

        //se não existe o determinado tipo, continua
        $class = $type."Type";
        if(!$this->getNewInstance($class)){return true;}
        
        //recupera a instancia do novo objeto
        $obj = $this->actions[$class];
        //echo "$name - $post\n";
        //valida o tipo da classe
        if(!$obj->validate($name, $post)){
            $this->setErrorMessage($obj->getErrorMessage());
            return false;
        }
        return true;
    }
    
	public function filter($name, $array){
		$this->name = $name;
		$obj = $this->getObject($array);
		if(is_bool($obj)){return true;}
		return $obj->filter($name, $array);
	}
	
	public function genQuery($name,$array, $params){
		safeUnset(array('especial'), $array);
		$obj =  $this->getObject($array);
		if(is_bool($obj)){return true;}
		return $obj->genQuery($name, $array, $params);
	}
	
			private function getObject($array, $form = ""){
				if(array_key_exists("especial", $array) && $array['especial'] != 'hide'){return true;}
				//se não existe o determinado tipo, continua
				$class = $array['type']."Type";
				if(!$this->getNewInstance($class, $form)){ return true;}

				//recupera a instancia do novo objeto
				return $this->actions[$class];
			}
	
	public function format($dados, &$value){
		if(array_key_exists("especial", $dados) && $dados['especial'] != 'hide'){return true;}
        $class = $dados['type']."Type";
        if(!$this->getNewInstance($class)){return true;}
        $obj = $this->actions[$class];
        return $obj->format($dados, $value);
	}
	
    public function flush() {
        
    }
}