<?php

class validatorResource extends \classes\Interfaces\resource{
    /**
    * @uses Contém a instância do banco de dados
    */
    private static $instance = NULL;
    private $msg_form = array();
    
    private $post    = array();
    private $action  = array();
    private $editing = false;
    
    /**
    * Construtor da classe
    * @uses Carregar os arquivos necessários para o funcionamento do recurso
    * @throws DBException
    * @return retorna um objeto com a instância do banco de dados
    */
    public function __construct() {
        $this->dir = dirname(__FILE__);
        return $this->load();
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

   /**
    * @abstract Loader da classe
    * @uses Carregar os arquivos necessários para o funcionamento do recurso
    */
    public function load(){
        $this->LoadResourceFile("classes/loader.php");
        $this->LoadResourceFile("classes/armazem.php");
    }

    public function is_editing(){
        $this->editing = true;
    }
    
    /**
    * @abstract valida o formulario enviado
    * @param string $model  - model a partir do qual será gerado o formulário
    * @param array  $values - array contendo os valores enviados via post
    * @return nada
    */
    public function validate($dados, $post = array()){

        $bool = true;
        if($this->prepare($dados, $post) === false) {return false;}
        
        //verifica as regras do formulario
        foreach($dados as $field => $arr){
            
            //se dado nao foi enviado
            if(!array_key_exists($field, $this->post)){continue;}
            
            //se é uma chave estrangeira, ignora o tipo da variável
            if(array_key_exists("fkey", $arr)){
                if(array_key_exists('type', $arr)) 
                unset($arr['type']);
            }
            
            foreach($arr as $action => $value){
                if($this->executeAction($field, $action, $value) === false){$bool = false;}
            }
        }
        
        
        //echo "["; print_r($this->post); echo "]";

        return $this->genMessages($bool, $dados);
    }
    
    private function prepare($dados, $post){
        if(array_key_exists('antispam', $post)){
            if($post["antispam"] != ""){
                $this->setErrorMessage("Caro usuário, não preencha o campo antispam!");
                return false;
            }
            else {unset($post["antispam"]);}
        }
        
        if(!$this->validaUrl()) {return false;}
        $this->post = $post;
        $arm = armazem::getInstanceOf();
        $arm->setVars($dados);
        $arm->addVar($post, 'post');
        return true;
    }
    
    private function executeAction($field, $action, $value){
        //carrega o arquivo do recurso
        $class = "{$action}Action";
        if(!$this->LoadResourceFile("lib/action/$class.php", false)){return true;}

        if(!array_key_exists($class, $this->action)){$this->action[$class] = new $class();}

        $obj = $this->action[$class];
        if($obj->validar($field, $value, $this->post[$field]) === false){
            $this->msg_form[$field] = $obj->getErrorMessage();
            $this->setMessage($field, $this->msg_form[$field]);
            return false;
        }
        return true;
    }
    
    private function genMessages($bool, $dados){
        if($bool){return true;}
        $msgs = $this->getMessages();
        foreach($msgs as $campo => $msg){
            if($msg == "") {continue;}
            $camp_name = isset($dados[$campo]['name'])?$dados[$campo]['name']:$campo;
            $erro = is_array($msg)?implode(" ", $msg):$msg;
            $this->setErrorMessage("$camp_name: $erro");
        }
        $this->setErrorMessage("Erro ao validar Formulário!");
        return $bool;
    }
    
    public function getValidPost(){
       //echo "{"; print_r($this->post); echo "}";
    	return $this->post;
    }
    
    /**
    * @abstract retorna o valor do campo validado
    * @param string $campo  - nome do campo
    * @return valor do campo, caso a variavel seja uma string, ou todos os campos 
    *   caso a variavel seja vazia 
    */
    public function FieldText($campo = "")
    {
        if(!empty ($this->post)){

            if(array_key_exists($campo, $this->post)){
                return $this->post[$campo];
            }
            else{
                return $this->post;
            }
        }
        return "";
    }

    /**
    * @abstract verifica se um campo de um select input está selecionado
    * @param string $campo  - nome do campo
    * @param string $valor  - valor a ser selecionado
    * @return SELECTED caso seja selecionado, ou vazio caso contrário
    */
     public function FieldSelect($campo, $valor)
     {
         if(!empty ($_REQUEST)){
            if(array_key_exists($campo, $_REQUEST)){
                if($_REQUEST[$campo] == $valor){
                    return "SELECTED";
                }
            }
         }
         return "";
     }
     
   /**
    * @abstract Retorna a resposta da validacao do campo (ex: este campo deve ser selecionado)
    * @param string $campo  - nome do campo
    * @return retorna a mensagem de erro do campo
    */
    public function Answer($campo = ""){
        if(!empty ($this->msg_form)){
            if(array_key_exists($campo, $this->msg_form)){
                return $this->msg_form[$campo];
            }elseif($campo == ""){
                return $this->msg_form;
            }
        }
    }
    
    /*
     * Verifica se os dados enviados estão sendo feitos pelo próprio site. 
     * Se não for, não salva nenhum dado!
     */
    private function validaUrl(){
        $str = validaUrl();
        if($str === true) return true;
        $this->setErrorMessage($str);
        return false;
    }
}