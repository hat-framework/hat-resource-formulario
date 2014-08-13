<?php

use classes\Classes\Object;
abstract class DinamicForm extends classes\Classes\Object{

    protected $id, $type, $array, $form, $target, $option;
    abstract public function execute();
    abstract public function flush();
    
    public final function setId($id){
        $this->id = $id;
    }
    
    public final function setType($type){
        $this->type = $type;
        $this->target = $this->type['target'];
        $this->option = $this->type['option'];
    }
    
    public final function setArray($array){
        $this->array = $array;
    }
    
    public final function setForm($form){
        $this->form = $form;
    }
}

?>