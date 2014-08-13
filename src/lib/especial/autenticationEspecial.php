<?php

class autenticationEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        
        //se já existe um valor cadastrado
        if($valor != "" && $valor != 0) return true;
        
        //recupera o código do usuário
        $usuario = usuario_loginModel::CodUsuario()."";
        //se código inválido, verifica se ele é necessário
        if($usuario === 0){
            $arm = armazem::getInstanceOf();
            $arr = $arm->getVar($campo);
            $arr = $arr['autentication'];
            if($arr['needlogin']){
                $this->setErrorMessage("É preciso fazer login para executar esta ação");
                return false;
            }
        }
        $valor = $usuario;
        return true;
    }
    
    public function js($campo, $array, $form){
        
        if($array['autentication']['needlogin']){
            $this->LoadModel("usuario/login", 'uobj');
            $this->uobj->needLogin(URL);
        }
        
        $var = $form->getVar("__$campo");
        if($var == "" || $var == 0) $var = "";
        $form->hidden($campo, $var);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}