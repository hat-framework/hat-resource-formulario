<?php

class typeResource extends \classes\Interfaces\resource{
    /**
    * @uses Contém a instância do banco de dados
    */
    private static $instance = NULL;

    
    /**
    * Construtor da classe
    * @uses Carregar os arquivos necessários para o funcionamento do recurso
    * @throws DBException
    * @return retorna um objeto com a instância do banco de dados
    */
    public function __construct() {
        $this->dir = dirname(__FILE__);
    }
    
    /**
    * retorna a instância do banco de dados
    * @uses Faz a chamada do contrutor
    * @throws DBException
    * @return retorna um objeto com a instância do banco de dados
    */
    public static function getInstanceOf(){
        $class_name = __CLASS__;
        if (!isset(self::$instance))self::$instance = new $class_name;
        return self::$instance;
    }
    
    public function LoadType($type){
        return $this->LoadClass('types', "{$type}Type");
    }
    
    public function LoadEspecial($especial){
        return $this->LoadClass('especial', "{$especial}Especial");
    }
    
    private function LoadClass($path, $class){
        static $instances = array();
        if(array_key_exists($class, $instances)){
            return $instances[$class];
        }
        
        $file  = dirname(__FILE__) . "/lib/$path/$class.php";
        if(!file_exists($file)){
            $instances[$class] = null;
            return $instances[$class];
        }
        require_once $file;

        if(!class_exists($class)) die("A classe $class não existe!");
        $instances[$class] = new $class();
        return $instances[$class];
    }

}

?>