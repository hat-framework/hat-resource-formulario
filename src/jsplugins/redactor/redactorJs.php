<?php

use classes\Classes\JsPlugin;
class redactorJs extends JsPlugin{
        
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init(){
        $this->Html->LoadBowerComponent('redactor/redactor/redactor.min');
        $this->Html->LoadBowerComponent('hatframework-hatjs-form/redactor.plugin');
        $this->Html->LoadBowerComponentCss('redactor/redactor/redactor');
    }

    private $options = array(
        'format' => "'bold','italic','deleted','|','outdent','indent'",
        'image'  => "'image'",
        'video'  => "'video'",
        'list'   => "'unorderedlist', 'orderedlist'",
        'link'   => "'link'",
        'align'  => "'alignment'",
        'table'  => "'table'",
        'file'   => "'file'",
        'color'  => "'fontcolor', 'backcolor'",
        'regua'  => "'horizontalrule'",
        'font'   => "'formatting', 'fontcolor', 'backcolor'",
        'html'   => "'html'",
    );
    //private $default_options = array('format', 'image', 'list', 'link', 'font');
    private $default_options = array('format', 'image', 'list', 'link', 'font', 'file', 'video');
    private $buttons = "";
    public function setOptions($options){
        if(empty ($options)) $options = $this->default_options;
        $add = "";
        $this->buttons .= "'fullscreen', ";
        $last = '';
        foreach($options as $option){
            if(!array_key_exists($option, $this->options)) continue;
            if(in_array($option, array('file', 'video', 'image'))){
                $last .= ",".$this->options[$option];
                continue;
            }
            $this->buttons .= $add.$this->options[$option];
            $add = ",'|',";
        }
        $this->buttons .= ",'|'".$last;
    }
    
    private $textareaedit = false;
    public function execute($campo, $form){
        if($this->buttons == "") $this->setOptions (array());
        if($this->buttons != "") $this->buttons = "[$this->buttons]";

        $campo = ($campo[0] != "#" && $campo[0] != ".")?"textarea.edit":"$campo";
        if($campo == "textarea.edit" && $this->textareaedit)return;
        $this->textareaedit = true;
        
        $this->LoadJsPlugin('formulario/jqueryvalidate', 'cal');
        $this->cal->addToReset("$('$campo').setCode('');");
        
        //$file_upload_url = $this->Html->getLink('files/arquivo/upload');
        $image_upload_url = URL_RESOURCES. "upload/lib";
        $this->Html->LoadJsFunction("
            new __redactor_plugin('$campo', '$image_upload_url', $this->buttons);
        ");
        $this->buttons = '';
    }
}