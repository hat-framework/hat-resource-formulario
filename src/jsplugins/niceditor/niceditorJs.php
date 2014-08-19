<?php

use classes\Classes\JsPlugin;
class niceditorJs extends JsPlugin{
    
    public $project_url = 'http://nicedit.com/';
    public $file_sample = 'sample.php';
    private $config_nedit = "";
    
    public function init(){
        echo "<div id='panel' class='nicedit-panel'></div>";
        echo "<style type='text/css'>";
        echo    ".nicedit-panel{width: 100%;}";
        echo    ".edit{border: 1px solid #D9D9D9;}";
        echo "</style>";
        static $i = 0;
        if($i > 0) return;
        
        $i++;
        $icons  = $this->Html->getBowerComponentItem('NicEdit/nicEditorIcons.gif');
        $upload = $this->Html->getBowerComponentItem('NicEdit/upload.php');
        $this->config_nedit = "
            iconsPath : '$icons', 
            uploadURI : '$upload',
            alternative_path     : 'teste'";
            //,buttonList : ['bold','italic','underline', 'upload']";

        //$this->Html->LoadCss("formulario");
        $this->Html->LoadJs("$this->url/nicEdit", true);
        echo "<script type='text/javascript'>
            $(function() {
             nicEditors.allEditable({ $this->config_nedit })
            }); </script>";
    }
    
    static private $instance;
    public static function getInstanceOf($plugin){
        $class_name = __CLASS__;
        if (!isset(self::$instance))
            self::$instance = new $class_name($plugin);
        return self::$instance;
    }
}