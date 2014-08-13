<?php
//define("FORM_DIR", realpath(dirname(__FILE__)) . "/");

class editorResource extends \classes\Interfaces\resource{
    
    /**
    * @uses Contém a instância do banco de dados
    */
    private static $instance = NULL;
    
    /**
    * Construtor da classe
    * @uses Carrega os arquivos necessários do editor de textos
    */
    public function __construct() {
        require_once "config/editor.php";
    }
    
    /**
    * retorna a instância do editor de textos
    * @uses Faz a chamada do contrutor
    * @throws CannotInstanceJsException
    * @return retorna um objeto com a instância do editor de textos
    */
    public static function getInstanceOf(){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) self::$instance = new $class_name;
        return self::$instance->getEditor(FORMULARIO_EDITOR_DEFAULT);
    }
    
    /**
    * retorna a instância do editor de textos
    * @uses Faz a chamada do contrutor
    * @throws CannotInstanceJsException
    * @return retorna um objeto com a instância do editor de textos
    */
    public function getEditor($editor){
        if(!isset($this->ed) || is_object($this->ed)){
            try{
                $this->LoadJsPlugin("formulario/$editor", 'ed');
            } catch (\Exception $e){
                $this->LoadJsPlugin("formulario/redactor", 'ed');
            }
        }
        return $this->ed;
    }
    
}