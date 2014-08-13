<?php

class fknn extends fk{

    public function __construct($name) {
        parent::__construct($name);
        $this->LoadJsPlugin("formulario/jqtokeninput", "ac");
    }
        
    public function geraForm(){
        
        $this->initVars();
        $this->findKeys();
        if(!$this->verifyVars()) return;
        
        $arr      = $this->getArray();

        
        $keys     = $this->model_data[$this->sname]['fkey']['keys'];
        $model    = $this->model_data[$this->sname]['fkey']['model'];
        $values   = $this->getValues($keys);
        $filtro   = (array_key_exists('filther', $arr['fkey']))? $arr['fkey']['filther']:"";
        $this->ac->setQuery($filtro);
        
        $form = $this->getForm();
        $val  = implode(",", $values);
        $this->ac->autocomplete($this->names, $model, $keys, 10, "", $this->getModel());
        $form->text($this->names,$this->name, $val, $this->desc);
    }
    
    public function validar($value, &$array) {
        if($array == "") return true;
        if(!is_array($array)){
            $array = explode(",", $array);
            if(count($array) == 1) $array = array_shift($array);
        }
        if(empty ($array)) $array = "";
        return true;
    }
    
    
     private function initVars(){
         
        //recupera as variaveis da classe mãe
        $name = $this->getName();
        $arr  = $this->getArray();
        
        $this->names      = str_replace(array("[", "]"), "", $name);
        $this->name_arr   = $arr['name']; 
        $this->formmodel  = isset($arr['fkey']['formmodel'])?$arr['fkey']['formmodel'] :$this->formmodel;
        $this->model_data = $this->model_obj->getDados();       
        $this->pkeys      = $this->model_obj->getPkey();
        $this->sname      = $this->tname = "";
        $this->desc       = (array_key_exists('description', $arr))?$arr['description']:"";
    }
    
    /**
     * Método auxiliar de gera form
     */
    private function findKeys(){
        $arr = $this->getArray();
        $model_src = $arr['fkey']['formmodel'];
        $this->ac->setForm($this->getForm());
        foreach($this->pkeys as $pk){
            $dado    = $this->model_data[$pk];
            $temp_md = $dado['fkey']['model'];
            if($temp_md == $this->formmodel){
                $this->formmodel = "";
                $this->tname = $pk;
            }else{
                
                $this->sname = $pk;
                $this->name  = $arr['name'];
                if($model_src != $temp_md){
                    $this->comp  = new classes\Component\Component();
                    $this->name .= " " . $this->comp->getFkeyLink($temp_md, $dado['fkey'], $this->names);
                }else{
                    
                }
            }
        }
    }
    
    /**
     * Método auxiliar de gera form
     */
    private function verifyVars(){
        if(!array_key_exists($this->tname, $this->model_data) ||
           !is_array($this->model_data[$this->tname]['fkey']) ||
           !array_key_exists('keys', $this->model_data[$this->tname]['fkey'])) {
           if(DEBUG) {
               echo "<h1>Warning: FkeyAction - Não foi possível encontrar a referência para o formulário,
                favor verificar se no modelo a variável [fkey][formmodel] foi configurada corretamente</h1>";
           }
           $this->form->text($this->names,$this->name, '', $this->desc);
           return false;
        }
        return true;
    }
    
    /**
     * Método auxiliar de gera form
     */
    private function getValues($keys){
        $data = $this->getData();
        $arr  = $this->getArray();
        $val  = $this->prepopulate();
        
        $k1     = array_shift($keys);
        $k2     = array_shift($keys);
        $values = array();
        if(!empty($val)){
            foreach($val as $value){
                if(!isset($values[$value[$k1]])){
                    $values[$value[$k1]] = $value[$k1];
                    $this->ac->addItem($value[$k1], $value[$k2], true);
                }
            }
        }
        
        if(is_array($data)) {$selected = $data;}
        else                {$selected = (array_key_exists('default', $arr))  ?$arr['default']  :array();}
        if(empty($selected)) return array();
        foreach($selected as $value){
            if(!isset($values[$value[$k1]])){
                $values[$value[$k1]] = $value[$k1];
                $this->ac->addItem($value[$k1], $value[$k2]);
            }
        }
        return $values;
    }
    
    
    private function prepopulate(){
        $arr = $this->getArray();
        if(!isset($arr['fkey']['populate'])) return array();
        $pre = $arr['fkey']['populate'];
        extract($pre);
        if(!isset($find_session) || !isset($_SESSION[$find_session])) return array();
        
        $this->LoadModel($model, 'fnd');
        if(isset($join)){
            $this->LoadModel($join, 'jmd');
            $this->fnd->db->Join($this->fnd->getTable(),$this->jmd->getTable());
        }
        
        $wh = "$find = '{$_SESSION["$find_session"]}'";
        return $this->fnd->selecionar($coluna, $wh);
    }
    
}