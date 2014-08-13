<?php

use classes\Classes\Object;
abstract class block extends classes\Classes\Object{
    /**
    * @return retorna um array contendo dados para os campos de busca
    */
    public abstract function getSearchData();
    
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
}

?>