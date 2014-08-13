<?php

use classes\Classes\JsPlugin;
class jqueryvalidateJs extends JsPlugin{
    
    private $validation = array();
    private $val_message = array();
    private $masks  = "";
    private $output = "";
    private $formname = "";
    private $reset = "";
    private $success = "";
    
    public function init() {
        $this->LoadResource("html", "Html");
        $this->LoadJsPlugin('jqueryui/blockui', 'bui');
        $this->Html->LoadJs("$this->url/js/clearform");
        $this->Html->LoadJs("$this->url/js/maskedin");
        $this->Html->LoadJs("$this->url/js/jquery.validate.min");
        $this->Html->LoadJs("$this->url/js/jq.validate.actions");
    }
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) {
            self::$instance = new $class_name($plugin);
        }

        return self::$instance;
    }
    
    public function setCurrentForm($form){
        $this->formname = $form;
        $this->formquene[] = $form;
        $this->masks[$form] = "";
        $this->validation[$form] = array();
        $this->val_message[$form] = array();
    }
    
    public function addValidation($field, $validation, $message){
        $this->validation [$this->formname][$field][]  = $validation;
        $this->val_message[$this->formname][$field][]  = $message;
    }
    
    public function addMask($mask){
        $this->masks[$this->formname] .= $mask . " \n\n\t ";
    }
    
    public function addToReset($function){
        $this->reset .= $function;
    }
    
    public function addToSuccess($function){
        $this->success .= $function;
    }
    
    public function validate($form, $ajax){
        
        $rll  = $this->arr2jsonval($this->validation[$form] );
        $msgs = $this->arr2jsonval($this->val_message[$form]);
            
        $this->Html->LoadJQueryFunction("
            {$this->masks[$form]}
            $('input[type=\"submit\"]').on('click', function(ev){
                var rules = $rll;
                var msgs  = $msgs;
                jqValidate_submit($(this), rules, msgs, function(){ $this->success alert('s'); }, function(){ $this->reset alert('r'); });
            });
        ");
        
        //reseta as variaveis
        unset($this->validation[$form]);
        unset($this->val_message[$form]);
        unset($this->masks[$form]);
        $this->formname = array_shift($this->formquene);
        $this->reset = "";
    } 
    
    private function arr2jsonval($arr){
        $vig   = "";
        $str = "{";
        foreach($arr as $field => $rules){
            $rule = implode(", ", $rules);
            $str .= "$vig \"$field\":{ $rule }";
            $vig = ",";
        }
        $str .= " }";
        return $str;
    }
    
}

?>