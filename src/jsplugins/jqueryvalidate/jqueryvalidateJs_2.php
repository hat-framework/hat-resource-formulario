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
        if($form == "") die(__METHOD__ . " - " . __LINE__. ": nome do formulário vazio!");
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
        echo $form . "<hr/>";
        //if(!$this->is_started($form)) $this->setCurrentForm ('');
        $esp = (DEBUG)?"":"";
        
        //print_r($this->validation[$form]);
        //echo json_encode($this->validation[$form], JSON_OBJECT_AS_ARRAY);
        $vig = "$esp";
        $this->output .= " ".$this->masks[$form];
        $ajax_function = $this->ajax($ajax, $form);
        
        $ajax = ($ajax)?"if(tform.find('#ajax').length === 0){
                    tform.append('<input type=\"hidden\" id=\"ajax\" name=\"ajax\" value=\"true\" />');
                }":"";
        $this->output .= "
            $('input[type=\"submit\"]').on('click', function(ev){
                var tform = $(this).parents('form');
                $ajax
                $(tform).validate({";
                    if(!empty ($this->validation[$form])){
                        $this->output .= "rules:{";
                        foreach($this->validation[$form] as $field => $rules){
                            $rule = implode(", ", $rules);
                            $this->output .= "$vig \"$field\":{ $rule }";
                            $vig = ", $esp";
                        }
                        $vig = "";
                        $this->output .= " } , $esp$esp messages:{";
                        foreach($this->val_message[$form] as $field => $messages){
                            $msg           = implode(", ", $messages);
                            $this->output .= "$vig \"$field\":{ $msg }";
                            $vig = ", $esp";
                        }
                        $this->output .= "},$esp
                            success: function(label) {
                                tform.find('#v'+label.attr('for')).text('Ok!').removeClass('erro').addClass('valid_msg');
                            },$esp
                            errorPlacement: function(label, element) {
                                tform.find('#v'+label.attr('for')).html(label.text()).removeClass('valid_msg').addClass('erro'); 
                            }";
                            if($ajax_function != "")$this->output .= ", ";
                    }

                    if($ajax_function != ""){
                        $this->output .= "submitHandler: function(form) {
                            
                            $ajax_function
                        }";
                    }
                    $this->output .= "});
                        
               });
               ";
        
            
        $this->Html->LoadJQueryFunction($this->output);
        
        //reseta as variaveis
        if(isset($this->validation[$form]))  unset($this->validation[$form]);
        if(isset($this->val_message[$form])) unset($this->val_message[$form]);
        if(isset($this->masks[$form]))       unset($this->masks[$form]);
        $this->output = "";
        $this->formname = array_shift($this->formquene);
        $this->reset = "";
    } 
    
    private function ajax($ajax, $form){
        $var = "";
        if($ajax){
            $var = "
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
       return $var;
    }
    
}

?>