<?php

require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR. 'init.php';
use classes\Classes\Object;
class FormNNKey extends classes\Classes\Object{
    
    public static function verifica(){
        
        try{
            if(array_key_exists("model", $_GET)){
                $obj = new FormNNKey();
                $obj->execute($_GET['model']);
            }
        }catch (Exception $e){
            echo 'false';
            die();
        }
    }
    
    public function execute($model){
        
        $this->LoadModel($model, "model_obj");
        print_r($_POST);
        
    }
    
}
FormNNKey::verifica();