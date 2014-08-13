<?php

class enderecoEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $this->LoadJsPlugin('formulario/cep', 'end');
        extract($array);
        $this->end->endereco($endereco['cep'], $endereco['rua'], $endereco['bairro'], $endereco['cidade'], $endereco['estado']);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>