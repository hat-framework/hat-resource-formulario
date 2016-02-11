<?php

class formularioResource extends \classes\Interfaces\resource{
    
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
        if (!isset(self::$instance)) {
            self::$instance = new $class_name;
        }

        return self::$instance;
    }
    
    public function setModel($model){
        $this->form->setModel($model);
    }
    
    
   /**
    * @abstract gera o formulario
    * @param string $model  - model a partir do qual será gerado o formulário
    * @param array  $modify - array contendo os modificadores das opções default do formulario
    * @return nada
    */
    public function execute($model, $values = array(), $modify = array(), $url = ""){
        $this->form->setModel($model);
        $this->form->Open($url);
        $this->form->setVars($values);
        
        
        //cria os campos do formulario
        $this->LoadModel($model, "model");
        $temp  = $this->model->getDados();
        $dados = array_merge_recursive($temp, $modify);
        $this->genForm($dados);

        return $this->form->Close();
    }
    
    
    /*
     * @uses Gera elementos para o formulário mas sem criar a tag <form>
     * Útil quando se deseja adicionar campos ao formulário dinâmicamente
     */
    public function genForm($dados, $values = array()){
        $this->form->setDados($dados);
        if(!empty($values)) {$this->form->setVars($values);}
        foreach($dados as $name => $array){
            if(array_key_exists("ai", $array)&&$array['ai']) {$array['especial'] = 'hidden';}
            elseif(array_key_exists("private", $array)&&$array['private']) {continue;}
            elseif(array_key_exists("fkey", $array)) {unset($array['type']);}
            if(array_key_exists("default", $array) && !$this->form->hasVar($name)){
                $varvalue = $array['default'];
                $this->form->setVars($name, $varvalue);
            }
            $this->executeAction($name, $array);
        }
        $this->doFlush();
    }
    
            private function doFlush(){
                 foreach(self::$act as $class){
                    $obj = isset($this->action[$class])?$this->action[$class]:null;
                    if(!is_object($obj)){continue;}
                    $obj->flush();
                }
            }
    
    static $act = array();
    public function executeAction($name, $array){
        if(empty($array) || !is_array($array)) {return;}
        if(isset($array['notnull']) && $array['notnull'] == true){$array['name'] .= " *";}
        foreach($array as $action => $value){
            $class = "$action"."Action";
            if(!$this->LoadResourceFile("lib/action/$class.php", false)) {continue;}
            if(!array_key_exists($class, $this->action)) {
                $this->action[$class] = new $class();
                self::$act[$class] = $class;
            }
            $obj = $this->action[$class];
            $obj->executar($name, $value, $array, $this->form);
        }
        
    }
    
    /*
     * @uses Retorna o formHelper
     */
    public function getForm(){
        return $this->form;
    }
    
    /*
     * @uses Cria um novo formulário, independente de qualquer formulário
     * que esteja sendo criado antes de dar flush, útil para 
     * os casos onde existe formulários dependentes dentro da mesma página
     */
    public function NewForm($dados, $values = array(), $modify = array(), $ajax = true, $action = "", $abs_url = false){
        $this->form->Open($action, "", $ajax, $abs_url);
        $this->form->setVars($values);
        $this->genForm($dados, $modify);
        return $this->form->Close();
    }
    
    public function closeform(){
        return $this->form->Close();
    }
    
    /*
     * @uses insira tags html, paragrafos ou outras coisas dentro do formulario
     */
    public function setData($data){
        $this->form->setData($data);
        return $this;
    }
    
    /*
     * @uses insira tags html, paragrafos ou outras coisas dentro do formulario
     */
    public function setMethod($method){
        $this->form->setMethod($method);
        return $this;
    }

    /*
     * @uses Seta o formulário como imprimivel na tela/ retorna a string contendo o formulario
     */
    public function printable(){
        $this->form->printable();
        return $this;
    }
    
    /*
     * @uses Seta o formulário como imprimivel na tela/ retorna a string contendo o formulario
     */
    public function omitir_cabecalho(){
        $this->form->omitir_cabecalho();
        return $this;
    }
    
    /*
     * @uses cria um formulário para ser exibido em uma tabela
     */
    public function setFormTable($bool){
        $this->form->setFormTable($bool);
        return $this;
    }
}