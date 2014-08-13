<?php

class armazem{
    
    private $vars = array();
    private static $instance = NULL;
    public static function getInstanceOf(){
        $class = __CLASS__;
        if(self::$instance == NULL){
            self::$instance = new $class();
        }
        return self::$instance;
    }
    
    public function setVars($vars){
        $this->vars = $vars;
    }

    public function addVar($var, $name){
        $this->vars[$name] = $var;
    }

    public function removeVar($var){
        if(array_key_exists($var, $this->vars)) unset($this->vars[$var]);
    }

    public function getVar($var){
        if(array_key_exists($var, $this->vars)) return($this->vars[$var]);
        return "";
    }
}