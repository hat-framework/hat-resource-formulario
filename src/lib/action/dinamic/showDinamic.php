<?php

class showDinamic extends DinamicForm{

    private static $test = array();
    public function execute(){
        
        if(!is_array($this->option)) $this->option = array($this->option);
        if(!isset(self::$test[$this->target])) self::$test[$this->target] = array();
        self::$test[$this->target][$this->id] = $this->option;
    }
    
    public function flush(){
        $obj = new classes\Classes\Object();
        $html = $obj->LoadResource('html', 'html');
        $html->LoadJs(URL_JS . "/lib/formulario/show_dinamic");
        $html->LoadJqueryFunction("{new show_dinamic(".  json_encode(self::$test).").bindAll();}");   
    }
}

?>