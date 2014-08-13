<?php

use classes\Classes\Object;
class GenFormHelper extends classes\Classes\Object {
       
    static private $instance;
    private $field_name = "";
    public static function getInstanceOf()
    {
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name();
        }

        return self::$instance;
    }
    
    public function geraForm($dados, $action = "", $is_editing = false, $onlyfields = false, $valores = array()){

        $this->valores = $valores;
        if(!is_array($dados) || empty ($dados)){
            return false;
        }
        
        $action =  ($action == "") ? strtolower("admin/".CURRENT_MODULE."/".CURRENT_CONTROLLER."/inserir") : $action;
        $action = URL_ADRESS . $action;
        $this->LoadHelper("Form", "form");
        $this->form->printable(); 
        $this->form->setRules($dados);

        if(!$onlyfields){
            $this->form->open($action, "", true);
            $this->form->Fieldset("", "");
        }
        
        foreach($dados as $name => $array){
            if(!array_key_exists("field", $array)){
                continue;
            }
            
            if(array_key_exists("only_edit", $array) && !$is_editing){
            	continue;
            }
            
            $this->array = $array;
            $this->field_name = $array['field'];
            
            if(array_key_exists($name, $this->valores)){
                $id = GetPlainName($this->field_name);
                $_REQUEST[$id] = $this->valores[$name];
            }
            
            foreach($array as $method => $value){
                if(method_exists($this, $method)){
                    $this->$method($value);
                }
            }

        }
        
        if(!$onlyfields){
                $this->form->CloseFieldset();
        	$this->form->close();
        }
    }
/*
 *  retorna a classe form que contém o formulario gerado
 */
    public function getForm(){
        return $this->form;
    }
    
    private function extern($array){
    	$model = $array['model'];
    	$this->LoadModel($model, "md_obj");
	$this->md_obj->geraForm($this->valores, true, true);    	
    }
    
    private function type($method){
        if(method_exists($this, $method)){
            $this->$method($this->field_name);
        }
    }
    
    /*
     * Tipos de dados no formulário 
     */
    private function text($field_name){
        $this->form->TextField($field_name, $field_name);
    }
    
    private function field_date($field_name){
        $id = GetPlainName($field_name);
        if(array_key_exists($id, $_REQUEST)) 
            $_REQUEST[$id] = convertData($_REQUEST[$id]);
        $this->form->TextField($field_name, $field_name);
    }
    
    private function textarea($field_name){
        
        $cols = 30; $rows = 5;
        if(array_key_exists('editor', $this->array)){
            $cols = 85; $rows = 10;
        }

        $this->form->TextArea($field_name, $field_name,"","", $cols, $rows);
    }
    
    private function select($field_name){
        $arr = $this->conection();
        $this->form->Select($field_name, $arr, $field_name);
    }
    
    private function radio($field_name){
        $arr = $this->conection();
        if(count($arr) > 10){
            $this->form->Select($field_name, $arr, $field_name);
        }else{
            $this->form->RadioCamp($field_name, $arr);
        }
    }
    
    private function checkbox($field_name){
        $array = $this->conection();
        $this->form->checkCamp($field_name, $array);
    }
    
    private function hidden($field_name){
        if(!array_key_exists("values", $this->array)){
            return;
        }
        $valor = ($this->array['values']);
        unset($this->array['values']);
        $this->form->HiddenField($field_name, $valor);
    }
    
    private function password($field_name){
        $this->form->PasswordField($field_name, $field_name);
    }
    
    private function file($field_name){
        $this->form->UploadButton($field_name, $field_name);
    }
    
    private function conection(){
        $temp_arry = array();
        if(array_key_exists("values", $this->array)){
            $temp_arry = ($this->array['values']);
        }
        
        if(!array_key_exists("conection", $this->array)){
            unset($this->array['values']);
            return $temp_arry;
        }
        $array   = $this->array['conection'];
        $model   = array_key_exists("model", $array)? $array['model'] : "";
        $coluna1 = array_key_exists("c1"   , $array)? $array['c1']    : "";
        $coluna2 = array_key_exists("c2"   , $array)? $array['c2']    : "";
        $where   = array_key_exists("where", $array)? $array['where'] : "";
        $this->LoadModel($model, "model_obj");
        $arr = ($this->model_obj->Acople($coluna1, $coluna2, $where));
        unset($this->array['conection']);
        return($temp_arry + $arr);
    }
    
    public function sample(){
        $dados = array(
            'select' => array(
                'field'     => 'Select Simples',
                'type'      => "select",
                'values'    => array(1 => 'teste', 2 => 'itworks', 3 => "muito easy")
             ),
            'select2' => array(
                'field'     => 'Select com coneção ao banco de dados',
                'type'      => "select",
                'conection' => array("model" => "rogerio/client", "c1" => "", "c2" => "cname", "where" => "")
             ),
            'descricao' => array(
                'field'     => 'Descrição',
                'type'      => "textarea"
             ),
            'radio' => array(
                'field'    => 'Radio Simples (se tiver mais do que 10 elementos vira select)',
                'type'     => 'radio',
                'values'   => array(1 => 'teste', 2 => 'itworks', 3 => "muito easy")
             ),
            'radio2' => array(
                'field'    => 'Radio Com conexão ao banco de dados(se tiver mais do que 5 elementos vira select)',
                'type'     => 'radio',
                'conection' => array("model" => "rogerio/client", "c1" => "", "c2" => "cname", "where" => "")
             ),
            'checkbox' => array(
                'field'    => 'checkbox simples',
                'type'     => 'checkbox',
                'values'   => array(1 => 'teste', 2 => 'itworks', 3 => "muito easy")
             ),
            'checkbox2' => array(
                'field'    => 'checkbox com conexão ao banco de dados',
                'type'     => 'checkbox',
                'conection' => array("model" => "rogerio/client", "c1" => "", "c2" => "cname", "where" => "")
             ),
            'hidden' => array(
                'field'    => 'campo oculto (esta mensagem não aparecerá)',
                'type'     => 'hidden',
                'values'   => 'se aparecer é mentira!!'
             ),
            'senha' => array(
                'field'    => 'Esta é a senha',
                'type'     => 'password'
             ),
            'confirm' => array(
                'field'    => 'Esta é a confirmação da senha',
                'type'     => 'password'
             ),
            'confirm' => array(
                'field'    => 'Exemplo com campo de arquivos (não é imagem)',
                'type'     => 'file'
             )
        );
        
        $this->geraForm($dados);
        echo ($this->getForm());
    }
}

?>
