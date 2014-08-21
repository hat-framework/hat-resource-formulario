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
        $this->Html->LoadBowerComponent("hatframework-hatjs-form/clearform");
        $this->Html->LoadBowerComponent("jquery-maskedinput/dist/jquery.maskedinput.min");
        $this->Html->LoadBowerComponent("jquery-validation/dist/jquery.validate.min");
        $this->Html->LoadBowerComponent("jquery-validation/dist/additional-methods.min");
    }
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function setCurrentForm($form){
        if(trim($form) == "") {die(__METHOD__ . " - " . __LINE__. ": nome do formulário vazio!");}
        $this->formname           = $form;
        $this->formquene[]        = $form;
        $this->masks[$form]       = "";
        $this->validation[$form]  = array();
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
    
    public function is_started($form){
        return (isset($this->masks[$form]));
    }
    
    public function validate($form, $ajax){
        $formid = ($form == "")?"":"#$form ";
        $rules         = $this->getValidationRules($form, $ajax);
        if(isset($this->masks[$form])) $this->Html->LoadJQueryFunction($this->masks[$form]);
        $this->Html->LoadJQueryFunction("
            $('{$formid}input[type=\"submit\"]').on('click', function(ev){
                var tform = $(this).parents('form'); $rules
            });");
        $this->reset($form);
    } 
    
    private function reset($form){
        if(isset($this->validation[$form]))  unset($this->validation[$form]);
        if(isset($this->val_message[$form])) unset($this->val_message[$form]);
        if(isset($this->masks[$form]))       unset($this->masks[$form]);
        $this->output = "";
        $this->formname = array_shift($this->formquene);
        $this->reset = "";
    }
    
    private function getAjaxAppend($ajax){
        return "if(tform.find('#ajax').length === 0){
            tform.append('<input type=\"hidden\" id=\"ajax\" name=\"ajax\" value=\"true\" />');
        }";
    }
    
    private function getValidationRules($form, $ajax){
        $output = $vig = "";
        if(!empty ($this->validation[$form])){
            $output .= "rules:{";
            foreach($this->validation[$form] as $field => $rules){
                $rule = implode(", ", $rules);
                $output .= "$vig \"$field\":{ $rule }";
                $vig = ", ";
            }
            $vig = "";
            $output .= " } , messages:{";
            foreach($this->val_message[$form] as $field => $messages){
                $msg     = implode(", ", $messages);
                $output .= "$vig \"$field\":{ $msg }";
                $vig = ", ";
            }
            $output .= "},
            success: function(label) {
                tform.find('#v'+label.attr('for')).text('Ok!').removeClass('erro').addClass('valid_msg');
            },
            errorPlacement: function(label, element) {
                tform.find('#v'+label.attr('for')).html(label.text()).removeClass('valid_msg').addClass('erro'); 
            }";
        }

        $ajax_rules = "";
        if($ajax){
            $ajax_rules = $this->getAjaxAppend($ajax);
            if($output != "") {$output .= ",";}
            $ajax_function = $this->ajaxSubmit($ajax);
            $output .= "submitHandler: function(form) {
                $ajax_function
            }";
        }
        
        return "$ajax_rules $(tform).validate({ $output });";
    }
    
    private function ajaxSubmit($ajax){
        return "
        $.ajax({
            url: $(form).attr('action'),
            type: 'POST',
            data: $(form).serialize(),
            dataType: 'json',
            beforeSend: function(){
                blockUI_wait(\"Salvando...\");
                $('#erro').hide();
                $('#success').hide();
                $('.erro').delay('1200').fadeOut('slow');
                $('.valid_msg').delay('1200').fadeOut('slow');
            },
            success: function(json) {
                blockUI_unwait();
                if(typeof json.redirect != 'undefined') {
                    window.location.href = json.redirect;
                }

                var msg = '';
                if(json.status == 1){
                    if(json.is_editing != 1){                                    
                        $(form)[0].reset();
                        $this->reset
                    }
                    $this->success
                    if (typeof json.success != 'undefined' && json.success != '') blockUI_success(json.success);
                    else if(typeof json.alert != 'undefined' && json.alert != '') blockUI_alert(json.alert);
                    else blockUI_error('Dados enviados corretamente, mas sem a confirmação do servidor.');
                }else{
                    if (typeof json.erro != 'undefined') {
                        if(json.erro == '')json.erro = 'Erro ao validar dados no formulário!';
                        blockUI_error(json.erro);
                    }
                    else if(typeof json.alert != 'undefined' && json.alert != '') blockUI_alert(json.alert);
                    else blockUI_error('Dados enviados corretamente, mas sem a confirmação do servidor.');
                    for (var camp in json){
                         $('#v'+camp).text(json[camp]).fadeIn('slow').addClass('erro').removeClass('valid_msg');
                    }
                }

            },
            error: function(erro){
                blockUI_unwait();
                blockUI_error(\"Erro na comunicação com o site.<hr/> Detalhes: \"+erro['responseText']);
            }

        });";
    }
    
}