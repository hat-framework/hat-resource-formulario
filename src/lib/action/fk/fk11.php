<?php

class fk11 extends fk{

    public function geraForm(){
        $dados = $this->model_obj->getDados();
        foreach($dados as $name => $dado)$out[$name] = $dado;
        $this->LoadResource("formulario", "fgen");
        $d = $this->getData();
        if(isset($out['button'])) unset($out['button']);
        $this->fgen->genForm($out, $d);
    }
    
    public function validar($value, &$array) {
        return true;
    }
}

?>