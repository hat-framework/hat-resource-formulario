<?php

use classes\Classes\JsPlugin;
class tinymceJs extends JsPlugin{
        
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance)) self::$instance = new $class_name($plugin);
        return self::$instance;
    }
    
    public function init(){
        $this->Html->LoadBowerComponent('tinymce/jquery.tinymce.min');
    }
    
    private $options = array(
        'format'      => array('opt' => 'bold,italic,underline', 'reg' => 'r1', 'plugin' => '', 'aditional' => ''),
        'align'       => array('opt' => 'justifyleft,justifycenter,justifyright, justifyfull', 'reg' => 'r1', 'plugin' => '', 'aditional' => ''),
        'list'        => array('opt' => 'bullist,numlist', 'reg' => 'r1', 'plugin' => '', 'aditional' => ''),
        'history'     => array('opt' => 'undo,redo', 'reg' => 'r1', 'plugin' => '', 'aditional' => ''),
        'link'        => array('opt' => 'link,unlink', 'reg' => 'r1', 'plugin' => '', 'aditional' => ''),
        'table'       => array('opt' => 'table', 'reg' => 'r2', 'plugin' => 'table', 'aditional' => ''),
        'image'       => array('opt' => 'uploadify,image,insertimage', 'reg' => 'r2', 'plugin' => 'uploadify, imagemanager', 'aditional' => ''),
        'date'        => array('opt' => 'insertdate,inserttime', 'reg' => 'r2', 'plugin' => 'insertdatetime', 'aditional' => 'plugin_insertdate_dateFormat : "%d/%m/%Y", plugin_insertdate_timeFormat : "%H:%M:%S"'),
        'preview'     => array('opt' => 'preview', 'reg' => 'r3', 'plugin' => 'preview', 'aditional' => 'plugin_preview_width : "500", plugin_preview_height : "600"'),
        'search'      => array('opt' => 'search,replace', 'reg' => 'r2', 'plugin' => 'searchreplace', 'aditional' => ''),
        'font-size'   => array('opt' => 'fontsizeselect', 'reg' => 'r2', 'plugin' => '', 'aditional' => 'theme_advanced_font_sizes: "8px, 10px,12px,14px,16px,20px,24px,30px,48px"'),
        'font-family' => array('opt' => 'fontselect', 'reg' => 'r2', 'plugin' => '', 'aditional' => ''),
        'color'       => array('opt' => 'forecolor,backcolor', 'reg' => 'r2', 'plugin' => '', 'aditional' => ''),
        //'test'       => array('opt' => '', 'reg' => '', 'plugin' => '', 'aditional' => ''),
    );
    private $default_options = array('format', 'image', 'list', 'link', 'history', 'table');
    private $buttons = array();
    private $plugins = array();
    private $aditional = array();
    public function setOptions($options){
        if(empty ($options)) $options = $this->default_options;
        foreach($options as $option){
            if(array_key_exists($option, $this->options)){
                if($this->options[$option]['opt']       != "") $this->buttons[$this->options[$option]['reg']][] = $this->options[$option]['opt'];
                if($this->options[$option]['plugin']    != "") $this->plugins[]   = $this->options[$option]['plugin'];
                if($this->options[$option]['aditional'] != "") $this->aditional[] = $this->options[$option]['aditional'];
            }
        }
        if(empty ($this->buttons)) $this->setOptions (array());
        
        $optout = array('r1' => '', 'r2' => '', 'r3' => '');
        foreach($this->buttons as $opt => $arr){
            $optout[$opt] .= implode(',separator,',$arr);
        }
        $this->buttons   = $optout;
        $this->plugins   = implode(',',$this->plugins);
        $this->aditional = (!empty($this->aditional))? implode(',',$this->aditional):"";
    }
    
    public function execute($campo, $form){
        if(empty ($this->buttons)) $this->setOptions (array());
        extract($this->buttons);
        //$fnm   = $form->getFormName();
        //$fname = ($fnm == "")?"":"#$fnm > ";
        $campo = "textarea.edit";
        $this->Html->LoadJsFunction("
        function tinyMceStart(){
            $('$campo').tinymce({
                // Location of TinyMCE script
                script_url : '$this->url/lib/tiny_mce.js',

                // General options
                theme : 'advanced',
                language : 'pt',  
                plugins : 'safari,advlink,wordcount, $this->plugins',
                document_base_url : '".URL."',
                convert_urls : false,

                // Theme options
                theme_advanced_buttons1 : 'removeformat,separator,$r1',
                theme_advanced_buttons2 : '$r2',
                theme_advanced_buttons3 : '$r3',
                theme_advanced_toolbar_location : 'top',
                theme_advanced_toolbar_align : 'left',
                theme_advanced_resize_horizontal : false,
                theme_advanced_resizing : true,
                //theme_advanced_statusbar_location : 'bottom',

                // Example content CSS (should be your site CSS)
                //content_css : 'css/content.css',

                // Replace values for the template plugin
                onchange_callback: function(editor) {
                    tinyMCE.triggerSave();
                },
                $this->aditional
            });
        }
        tinyMceStart();
        ");
        $this->buttons = array();
    }
}

?>