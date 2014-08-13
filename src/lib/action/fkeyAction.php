<?php

require_once 'fk/fk.php';
require_once 'fk/fk11.php';
require_once 'fk/fk1n.php';
require_once 'fk/fkn1.php';
require_once 'fk/fknn.php';
class fkeyAction extends classes\Classes\Object implements actionInterface{
    
    public function validar($name, $value, &$array){
        $class = "fk".$value['cardinalidade'];
        if(!class_exists($class, false)) return true;
        $fk = new $class($name);
        return $fk->validar($value, $array);
    }
    
    public function executar($name, $value, $array, $form){
        
        //verifica se é responsabilidade desta classe gerar o formulario
        if(array_key_exists("especial", $array)){
            $ignore = array('hide', 'hidden', 'autentication', 'session');
            if(in_array($array['especial'], $ignore)) return true;
        }
        
        //verifica se a classe existe
        if($value['cardinalidade'] == ""){
            die(__METHOD__ . " - " . __LINE__ . ": cardinalidade não setada para o campo $name");
        }
        $class = "fk".$value['cardinalidade'];
        if(!class_exists($class, false)) return true;
        $fk = new $class($name);
        $fk->setForm($form);
        $fk->setValue($value);
        $fk->setArray($array);
        $fk->setModel($value['model']);
        $fk->geraForm();
    }
   
    public function flush() {
        
    }
}
