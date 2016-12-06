<?php

require_once 'dinamic/DinamicForm.php';
class dinamicAction extends classes\Classes\Object implements actionInterface {
    
    private static $added = array();
    public function executar($name, $type, $array, $form){
        $method = $type['type'];
        $class  = "{$method}Dinamic";
        $file   = realpath(dirname(__FILE__)) ."/dinamic/$class.php";
        if(!file_exists($file)) {
            if(!DEBUG) return;
            throw new Exception("O arquivo $file não foi encontrado ou não existe! " . __CLASS__ . ":".__FUNCTION__);
        }
        require_once $file;
       
        if(!class_exists($class)){ 
            if(!DEBUG) return;
            throw new Exception("A classe $class não foi encontrada ou não existe! " . __CLASS__ . ":".__FUNCTION__);
        }
        self::$added[$class] = $class;
        $obj = new $class();
        $obj->setId("f_$name");
        $obj->setType($type);
        $obj->setArray($array);
        $obj->setForm($form);        
        $obj->execute();
    }
    
    public function flush(){
        foreach(self::$added as $class){
            $obj = new $class();
            $obj->flush();
        }
    }
    
	public function filter($name, $array){}
	
    public function validar($name, $type, &$array){
        return true;
    }

}