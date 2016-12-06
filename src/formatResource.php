<?php

class formatResource extends \classes\Interfaces\resource{
	/**
    * @uses Contém a instância do banco de dados
    */
    private static $instance = NULL;
    
    
    private $action = array();
    
    /**
    * Construtor da classe
    * @uses Carregar os arquivos necessários para o funcionamento do recurso
    * @throws DBException
    * @return retorna um objeto com a instância do banco de dados
    */
    public function __construct() {
        $this->dir = dirname(__FILE__);
        $this->LoadResourceFile("classes/loader.php");
		$this->LoadResourceFile("lib/action/typeAction.php");
		$this->LoadResourceFile("lib/action/especialAction.php");
		$this->typeAction     = new typeAction();
		$this->especialAction = new especialAction();
    }
    
    /**
    * retorna a instância do banco de dados
    * @uses Faz a chamada do contrutor
    * @throws DBException
    * @return retorna um objeto com a instância do banco de dados
    */
    public static function getInstanceOf(){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {self::$instance = new $class_name;}
        return self::$instance;
    }
	
	public function format($dados, &$value){
		if(isset($dados['especial']) && in_array($dados['especial'], array('hide', 'hidden'))){
			unset($dados['especial']);
		}
		if(isset($dados['especial'])){
			return $this->especialAction->format($dados, $value);
		}
		if(!isset($dados['type'])){print_rd($dados);}
		return $this->typeAction->format($dados, $value);
	}
}
