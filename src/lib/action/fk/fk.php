<?php

abstract class fk extends classes\Classes\Object{
    
    private $form = NULL;
    private $value     = "";
    private $arr       = array();
    private $model     = "";
    private $name      = "";
    private $data      = "";
    private $formmodel = "";
    abstract public function geraForm();
    abstract public function validar($value, &$array);
    
    public function __construct($name) {
        $this->setName($name);
        $this->LoadResource('html', 'html');
        $this->LoadResource('jqueryui/dialogs','cbox');
    }

    public function setForm($form){
        $this->form = $form;
        $this->setData($form->getVar($this->name));
        $this->setFormmodel($form->getModel());
    }
    public function getForm(){
        return $this->form;
    }

    public function setValue($value){
        $this->value = $value;
    }
    public function getValue(){
        return $this->value;
    }
    
    public function setArray($array){
        $this->arr = $array;
    }
    public function getArray(){
        return $this->arr;
    }
    
    public function setModel($model){
        $this->model = $model;
        $this->LoadModel($this->model, "model_obj");
    }
    public function getModel(){
        return $this->model;
    }
    
    private function setName($name){
        $this->name = $name;
    }
    public function getName(){
        return $this->name;
    }
    
    private function setData($data){
        $this->data = $data;
    }
    public function getData(){
        return $this->data;
    }
    
    private function setFormmodel($formmodel){
        $this->formmodel = $formmodel;
    }
    public function getFormmodel(){
        return $this->formmodel;
    }
}