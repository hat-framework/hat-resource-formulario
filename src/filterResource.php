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
	
	public function getQuery($params, $model_or_data) {
    $tb = "";
    if (!is_array($model_or_data)) {
      $tb = $this->LoadModel($model_or_data, "tmp_md")->getTable() . ".";
    }
    $dados = $this->getData($model_or_data);
    $queryData = array();
    foreach ($dados as $name => $array) {
      $this->prepareArray($array);
      $temp = $this->executeAction($name, $array, 'genQuery', $params);
      if (trim($temp) == "" || empty($temp)) {
        continue;
      }
      $tmp = $tb . $name;
      $count = 1;
      $queryData[$name] = str_replace($name, $tmp, $temp, $count);
    }
    
    return $this->groupQueryData($queryData, $dados);
  }
  
      private function groupQueryData($queryData, $dados){
        $out = array();
        foreach($queryData as $name => $val){
          if(!isset($dados[$name])){
            $out[$name] = $val;
            continue;
          }

          $array = $dados[$name];
          if(!isset($array['filter']) || !isset($array['filter']['group']) || true !== $array['filter']['group']){
            $out[$name] = $val;
            continue;
          }

          if(!isset($out[$dados[$name]['type']])){
            $out[$dados[$name]['type']] = $val;
            continue;
          }
          $out[$dados[$name]['type']] .= " OR $val";
        }
        return $out;
      }

  public function genFilter($model_or_data) {
    $this->form_data = $_GET;
    $dados = $this->getData($model_or_data);
    if (empty($dados)) {
      return;
    }
    $formData = array();
    foreach ($dados as $name => $array) {
      if (isset($array['private']) && $array['private'] === true) { continue; }
      if (isset($array['fkey']) && $array['fkey']['cardinalidade'] === 'n1') { continue; }
      $this->prepareArray($array);
      $temp = $this->executeAction($name, $array, 'filter');
      if (!is_array($temp)) {
        continue;
      }
      foreach ($temp as $key => $arr) {
        safeUnset(array('notnull', 'default'), $arr);
        $formData[$key] = $arr;
      }
    }
    if(!$this->needPrint($formData)){return "";}
    $formData['_button'] = array("button" => 'Pesquisar');
    return $this->LoadResource('formulario', 'form')
      ->printable()
      ->setMethod('get')
      ->NewForm($formData, $this->form_data, array(), false);
  }
  
      private function needPrint($formData){
        if(empty($formData)){return false;}
        $bool = false;
        foreach($formData as $name => $arr){
          if(isset($arr['ai']) && $arr['ai'] === true){continue;}
          $bool = true;
          break;
        }
        return $bool;
      }

      private function getData($model_or_data) {
        try {
          if (!is_array($model_or_data)) {
            return $this->LoadModel($model_or_data, 'md')->getDados();
          }
          return $model_or_data;
        } catch (Exception $ex) {
          return array();
        }
      }

      private function prepareArray(&$array){
				if(!array_key_exists("filter", $array)) {return false;}
				elseif(array_key_exists("fkey", $array) && $array['fkey']['cardinalidade'] !== '1n') {unset($array['type']);}
				safeUnset(array('notnull','default'), $array);
				return true;
			}
	
			private function executeAction($name, $array, $method, $params = array()) {
        if (empty($array) || !is_array($array)) {
          return;
        }
        if (isset($array['filter']) && empty($array['filter'])) {
          return;
        }
        $triedFkey = false;
        foreach ($array as $action => $value) {
          if($triedFkey) {
            break;
          }
          $obj = false;
          if(isset($array['fkey']) && $array['fkey']['cardinalidade'] !== '1n') {
            $obj = $this->getObject('fkey'); 
            $triedFkey = true;
          }
          else { $obj = $this->getObject($action); }
          if ($obj === false || !method_exists($obj, $method)) {
            continue;
          }
          try {
            $out = $obj->$method($name, $array, $params);
            if ($out === true) {
              continue;
            }
            return $out;
          } catch (Exception $ex) {
            echoBr($ex->getMessage());
            echoBr($name . " " . $value . " " . $array);
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
