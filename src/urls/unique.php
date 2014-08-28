<?php

require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR. 'init.php';
use classes\Classes\Object;
class FormUniqueKey extends classes\Classes\Object{
    
    public static function verifica(){
        
        try{
            if(array_key_exists("model", $_GET)){
                $obj = new FormUniqueKey();
                $obj->execute($_GET['model']);
            }
        }catch (Exception $e){
            echo 'false';
            die();
        }
        
    }
    
    public function execute($model){
        
        $this->LoadModel($model, "model_obj");
        $where = "";
        foreach($_POST as $name => $var){
            $where .= "`$name` = '$var'";
        }
        $arr = $this->model_obj->selecionar(array(), $where, 1);
        if(empty ($arr)) echo 'true';
        else{
            $arr = array_shift($arr);
            
            $atual = $comp = "";
            if(array_key_exists("atual", $_GET)) $atual =base64_decode($_GET['atual']);
            if(array_key_exists($name  , $arr) ) $comp = $arr[$name];
            
            if($atual == $comp) echo 'true';
            else                echo 'false';
        }
        
    }
    
}
FormUniqueKey::verifica();