<?php

use classes\Classes\Object;
class GenJsValidatorHelper extends classes\Classes\Object {

    private $field        = "";
    private $output       = "";
    private $validation   = array();
    private $val_message  = array();
    private $masks        = "";
    private function __construct(){
        //$this->LoadHelper("Js", "Js");
        //$this->LoadHelper("Html", "Html");
        //$this->LoadConfig("validator");
    }
    private $formname = "";

    static private $instance;
    public static function getInstanceOf(){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name;
        }

        return self::$instance;
    }
    
    public function setForm($formname){
        $this->formname = $formname;
    }
    
    //métodos de controle
    public function addValidation($validation, $message){
     
        $this->validation [$this->field][]  = $validation;
        $this->val_message[$this->field][]  = $message;
        
    }
    public function addMask($mask){
        $this->masks .= $mask;
    }
    public function genJs(){
        $form = $this->formname;
        $vig = "";
        $this->output .= "$this->masks $('#$form').validate({rules:{";
        foreach($this->validation as $field => $rules){
            $rule = implode(", ", $rules);
            $this->output .= "$vig \"$field\":{ $rule }";
            $vig = ",";
        }
        $vig = "";
        $this->output .= " } ,messages:{";
        foreach($this->val_message as $field => $messages){
            $msg           = implode(", ", $messages);
            $this->output .= "$vig \"$field\":{ $msg }";
            $vig = ",";
        }
        $this->output .= "} });";
        $this->Html->LoadJsFunctions($this->output);
    }

    
    // Funções de campos de texto
    private function equalTo($campo){
        
    }
    private function email($name){
        $validation = "email: true";
        $message    = "email: '".EMAIL_INVALIDO."'";
        $this->addValidation($validation, $message);
    }
    
    private function editor(){
        $this->Js->LoadPlugin("niceditor", "editor", "jqcal");
        $this->Js->jqcal->load($this->field);
    }
    
    private function data($value){
        //mensagens de validacao
        $validation = "minlength: $value";
        $message    = "minlength: 'Formato correto: 99/99/9999'";
        //$this->addMask("$('#$this->field').mask('99/99/9999');");
        $this->addValidation($validation, $message);
        
        $this->Js->LoadPlugin("jquerycalendar", "calendario", "jqcal");
        $this->Js->jqcal->load($this->field);
    }
    
    private function time($value){
        //mensagens de validacao
        $this->addMask("$('#$this->field').mask('99:99:99');");
    }
    
    private function cep($value){
        $this->length($value);
        $this->addMask("$('#$this->field').mask('99999999');");
    }
    private function antispam($name){
        $validation = "maxlength: 0";
        $message    = "maxlength: '".NAO_PREENCHA."'";
        $this->addValidation($validation, $message);
    }
    private function url($value){
        $validation = "url: true";
        $message    = "url:'".URL_INVALIDA."'";
        $this->addValidation($validation, $message);
    }
    
    //valores numéricos
    private function minvalue($valor){
        $validation = "min: $valor";
        $message    = "min: 'Entre com um valor maior ou igual a $valor'";
        $this->addValidation($validation, $message);
    }
    private function maxvalue($valor){
        $validation = "max: $valor";
        $message    = "max: 'Entre com um valor menor ou igual a $valor'";
        $this->addValidation($validation, $message);
    }
   
    private function isNumeric($name){
        $validation = "number: true";
        $message    = "number: '".CAMPO_NUMERICO."'";
        $this->addValidation($validation, $message);
    }
    
    private function positive($valor){
        $this->minvalue(0);
    }
    
    private function image($value){
        if(is_array($value) && !empty ($value)){
            echo "aa";
            $value['album'] = array_key_exists("album",$_SESSION)? $_SESSION['album']:$value['album'];
            $this->Js->LoadPlugin("uploadify", "upload", "uify");
            $this->Js->uify->configure($this->field, $value['album'], $value['usuario'], $value['folder']);
        }else{
            throw new HelperException("GenJsValidator", 
                    "os parametros album, pasta e usuario nao foram configurados no metodo image"
            );
        }
    }
    
    private function is_monetary($value){
        //$this->isNumeric($value);
        
    }
    
    public function sample(){
        $rules = array(
            "senha"     => array("required" => true,"minlength" => 6    ,"maxlength"  => 6),
            "confirmar" => array("required" => true,"password"  => true ,"length" => 8),
            "email"     => array("required" => true,"email"     => true),
            "conf_email"=> array("required" => true,"equalTo"   => true),
            "data"      => array('data'     => true),
            "cep"       => array("cep"      => true,"url" => true, "cpf" => true, "cnpj" => true, "telefone" => true),
            "number"    => array("minvalue"=> 5, "maxvalue" => 10, "is_monetary" => true)
        );
        echo $this->validate($rules);
    }
}
?>
