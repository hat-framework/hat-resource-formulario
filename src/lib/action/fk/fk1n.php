<?php

class fk1n extends fk{

    private $removeKeys = array();
    public function geraForm(){
        $this->initVars();
        $arr = $this->getArray();
        if(array_key_exists('filther', $arr)){
            $fk1n = new \classes\Model\FK1nModel();
            $this->filtro = $fk1n->filther($arr['filther']);
        }
        
        $type  = (array_key_exists('select_type', $arr))?$arr['select_type']:"simple";
        if($type == 'chosen'){return $this->chosen_input();}
        
        $count = $this->model_obj->getCount($this->filtro);
        if($type == 'simple' && ($count < 20)){
             $this->simple_input();
        }else{$this->token_input($type);}
    }
    
    public function validar($value, &$array) {
        if(is_array($array))
            $array = array_shift($array);
        elseif($array == "")
            $array = "FUNC_NULL";
        return true;
    }
    
    private function initVars(){
        //inicia o colorbox
        if(!isset($this->cbox)){
            $this->LoadResource('jqueryui/dialogs','cbox');
            $this->cbox->ajaxDialog('.addnew');
        }
        
        //recupera os dados setados na classe mãe
        $arr            = $this->getArray();
        $name           = $this->getName();
        $value          = $this->getValue();
        
        //seta os dados
        $this->comp     = new classes\Component\Component();
        $this->names    = str_replace(array("[", "]"), "", $name);
        $this->name_arr = $arr['name'];     
        $this->keys     = $value['keys'];
        $this->desc     = (array_key_exists('description', $arr))?$arr['description']:"";
        $this->selected = (array_key_exists('default', $arr))    ?$arr['default']  :"";
        $this->filtro   = "";
    }
    
            private function chosen_input(){
                $class = $this->LoadJsPlugin('formulario/choseninput', 'csn')->getClass();
                $this->simple_input($class);
            }

            private function simple_input($class = ""){
                $this->removeKeys = array();
                $data   = $this->model_obj->getDados();
                $arr    = $this->getArray();
                $k1     = @$this->keys[0];
                $k2     = $this->setFk1nJoin(@$this->keys[1], $data, 1);
//                $this->setFk1nJoin($k1, $data);
                
                
                $order  = array_key_exists('select_order', $arr)?$arr['select_order']:"$k2 ASC";
                $dados  = $this->model_obj->selecionar($this->keys, $this->filtro, '','', $order);
//                echo $this->model_obj->db->getSentenca(); print_rh($dados);
                $out = array();
                if(!array_key_exists('notnull', $arr) || $arr['notnull'] == false){
                    $this->selected = ($this->selected != "")?$this->selected:'0';
                    $out[''] = "Selecione uma opção";
                    //die($this->names);
                }

                if(!empty($dados)){
                    $this->prepareArray($dados, $k1, $k2, $out);
                }
                $form = $this->getForm();
                $form->select($this->names, $out, $this->selected, $this->name_arr, $this->desc, $class);
            }
            
                    private function setFk1nJoin($k1, $dados, $index){
                        if(!isset($dados[$k1]) || !isset($dados[$k1]['fkey']) || $dados[$k1]['fkey']['cardinalidade'] != '1n'){return $k1;}
                        $tb = $this->LoadModel($dados[$k1]['fkey']['model'], "tmp_md")->getTable();
                        $this->model_obj->join($dados[$k1]['fkey']['model'], $k1, $dados[$k1]['fkey']['keys'][0], 'LEFT');
                        $i     = 0;
                        foreach ($dados[$k1]['fkey']['keys'] as $k){
                            if($i++ == 0){continue;}
                            $this->keys[$index] = "$tb.$k";
                            $this->removeKeys[$k1] = "$tb.$k";
                            return "$k";
                        }
                    }
            
                    private function prepareArray($dados, $k1, $k2, &$out){
                        foreach($dados as $temp_arr){
                            $key   = $temp_arr[$k1];
                            $value = $temp_arr[$k2];
                            unset($temp_arr[$k1]);
                            unset($temp_arr[$k2]);
                            $out[$key] = $value;
                            if(empty($temp_arr)){continue;}
                            foreach($temp_arr as $temp){
                                $out[$key] .= " ( $temp )";
                            }
                        }
                    }

            private function token_input($type){
                $this->LoadJsPlugin("formulario/jqtokeninput", "ac");
                $data = $this->getData();
                if(is_array($data) && !empty ($data)){
                    $k1 = array_keys($data);
                    $k2 = array_values($data);

                    $k1 = array_shift($k1);
                    $k2 = array_shift($k2);
                    $this->ac->addItem($k1, $k2);
                }elseif($this->selected != "" && !is_array($this->selected)){
                    $ks = $this->keys;
                    $k1 = array_shift($ks);
                    $k2 = array_shift($ks);
                    $it = $this->model_obj->selecionar(array($k1, $k2), "$k1 = '$this->selected'");
                    if(!empty($it)){
                        $it = array_shift($it);
                        $this->ac->addItem($it[$k1], $it[$k2]);
                    }
                }

                $num   = ($type === 'multiple')?"":1;
                $form  = $this->getForm();
                $model = $this->getModel();
                $dados = $this->getValue();
                $this->ac->setForm($form);
                $this->ac->setQuery($this->filtro);
                $this->completeSelected($form, $dados['model']);
                $this->ac->autocomplete($this->names, $model, $this->keys, 10, $num, $dados['model']);
                $form->text($this->names, $this->name_arr, '', $this->desc);
            }

                    private function completeSelected($form, $model){
                        if(!isset($this->keys[0]) || !isset($this->keys[1])){return;}
                        $var = $form->getVar("__$this->names");
                        if(trim($var) === ""){
                            $var = $form->getVar($this->names);
                            if(trim($var) === ""){return;}
                        }
                        $ee = explode(",", $var);
                        foreach($ee as $i => &$e){
                            $e = trim($e);
                            if($e !== ""){continue;}
                            unset($ee[$i]);
                        }
                        $in   = implode("','", $ee);
                        $k1   = $this->keys[0];
                        $k2   = $this->keys[1];
                        $data = $this->LoadModel($model, 'fktmpmodel')->selecionar($this->keys, "$k1 IN ('$in')");
                        foreach($data as $dt){
                            $this->ac->addItem($dt[$k1], $dt[$k2]);
                        }
                    }
    
}