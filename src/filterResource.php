<?php

class filterResource extends \classes\Interfaces\resource{
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
        $this->LoadJsPlugin("formulario/jqueryvalidate", "js");
        $this->form = new FormHelper();
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
	
	public function getQuery($params, $model_or_data){
		$dados	   = $this->getData($model_or_data);
		$queryData = array();
		foreach($dados as $name => $array){
			$this->prepareArray($array);
			$temp = $this->executeAction($name, $array, 'genQuery', $params);
			if(trim($temp) == "" || empty($temp)){continue;}
			$queryData[$name] = $temp;
		}
		return $queryData;
	}
	
	public function genFilter($model_or_data){
		$this->form_data = $_GET;
		$dados			 = $this->getData($model_or_data);
		if(empty($dados)){return;}
		$formData    = array();
		foreach($dados as $name => $array){
			$this->prepareArray($array);
			$temp = $this->executeAction($name, $array, 'filter');
			if(!is_array($temp)){continue;}
			//$this->getFilterInformation($name,$array, $temp);
			foreach($temp as $key => $arr){
				safeUnset(array('notnull'), $arr);
				$formData[$key] = $arr;
			}
		}
		$formData['_button'] = array("button" => 'Pesquisar');
		$this->LoadResource('formulario','form')
			 ->setMethod('get')
			 ->NewForm($formData, $this->form_data, array(), false);
	}
	
			private function getData($model_or_data){
				try{
					if(!is_array($model_or_data)){
						return $this->LoadModel($model_or_data, 'md')->getDados();
					}
					return $model_or_data;
				} catch (Exception $ex) {
					return array();
				}
			}
			
			private function prepareArray(&$array){
				if(!array_key_exists("filter", $array)) {return false;}
				elseif(array_key_exists("fkey", $array)) {unset($array['type']);}
				safeUnset(array('notnull','default'), $array);
				return true;
			}
//			private function getFilterInformation($name,$array, &$out){
//				if(isset($array['filter']['type'])){
//					$out["1$name"] = array(
//						'name'	   => "1$name",
//						'type'	   => 'varchar',
//						'especial' => 'hidden',
//						'value'	   => $array['filter']['type']
//					);
//				}
//			}
	
			private function executeAction($name, $array, $method, $params = array()){
				if(empty($array) || !is_array($array)) {return;}
				foreach($array as $action => $value){
					$obj = $this->getObject($action);
					if($obj === false || !method_exists($obj, $method)){continue;}
					try{
						$out = $obj->$method($name,$array, $params);
						if($out === true){continue;}
						//echoBr("$method::$name");print_rh($out);
						return $out;
					} catch (Exception $ex) {
						echoBr($ex->getMessage());
						echoBr($name." ".$value." ".$array);
					}
				}
			}
			
					private function getObject($action){
						$class = "$action"."Action";
						if(!$this->LoadResourceFile("lib/action/$class.php", false)) {return false;}
						if(!array_key_exists($class, $this->action)) {
							$this->action[$class] = new $class();
						}
						return $this->action[$class];
					}
	
}
