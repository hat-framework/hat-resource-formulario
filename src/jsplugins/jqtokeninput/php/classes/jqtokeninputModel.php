<?php

use classes\Classes\Object;
class jqtokeninputModel extends classes\Classes\Object{
    public function complete($model, $keys, $tag, $limit = ""){
        $arr    = $keys;
        $arr_ks = $keys;
        $k1 = array_shift($arr);
        $k2 = array_shift($arr);
        
        $this->LoadModel($model, "model");
        $dados  = $this->model->getDados();
        $filther = "";
        if(isset($dados[$k1]['filther']))$filther .= $dados[$k1]['filther'];
        if(isset($dados[$k2]['filther']))$filther .= $dados[$k2]['filther'];
        
        if($k2 == $k1) $keys = array($k1);
        $tags  = explode(" ",$tag);
        $tag   = array_pop($tags);
        
        if($tag == "") return array();

        $antes = empty($tags) ? "":implode(" ", $tags);
        $where = "$k2 LIKE '$tag%'";
        if(array_key_exists('query', $_GET)) $where = base64_decode($_GET['query'])." AND ($where)";
        if($filther != "")                   $where = ($where == "")?$filther: "$filther AND ($where)";
        
        $orderby = "$k2 ASC";
        $var = $this->model->selecionar($arr_ks, $where, $limit, '', $orderby);
        //echo $this->model->db->getSentenca();
        if(empty($var)) {
            $where = "";
            if(array_key_exists('query', $_GET)) $where = base64_decode($_GET['query']);
            if($filther != "")                   $where = ($where == "")?$filther: "$filther AND ($where)";
            $var = $this->model->selecionar($arr_ks, $where, $limit, '', $orderby);
            if(empty($var)) return $var;
        }
        $out = array();
        $i = 0;
        
        $count = count($keys);
        foreach($var as $array){
            $out[$i]['id']   = $antes . " " .$array[$k1];
            $out[$i]['name'] = "<div class='tokeninput_item'>$antes ";
            if($count > 1){
                $j = 1;
                while(isset($keys[$j])){
                    $out[$i]['name'] .= "<span class='".$keys[$j]."'>".$array[$keys[$j]]."</span>";
                    $j++;
                }
            }
            $out[$i]['name'] .= "</div>";
            $i++;
        }
        return $out;
    }
    
    public function delete($model, $array){
        $valor = array_values($array);
        $chave = array_keys($array);
        $this->LoadModel($model, 'model');
        if(!$this->model->apagar($valor, $chave)){
            $this->setErrorMessage($this->model->getErrorMessage());
            return false;
        }
        $this->setSuccessMessage($this->model->getSuccessMessage());
        return true;
    }
    
    public function add($model, $array){
        $this->LoadModel($model, 'model');
        if(!$this->model->inserir($array)){
            $this->setErrorMessage($this->model->getErrorMessage());
            return false;
        }
        $this->setSuccessMessage($this->model->getSuccessMessage());
        return true;
    }
}

?>
