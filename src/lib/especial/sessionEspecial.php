<?php

class sessionEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        
        if($valor == ""){
            $arm     = armazem::getInstanceOf();
            $arr     = $arm->getVar($campo);
            $session = $arr['session'];
            if($session == "" || !isset($_SESSION[$session])) return true;
            $valor = $_SESSION[$session];
        }else $valor =  \classes\Classes\crypt::decrypt_camp($valor); 
        return true;
    }
    
    public function js($campo, $array, $form){
        $session = isset($array['session'])?$array['session']:"";
        if($session != "" && array_key_exists($session, $_SESSION)){
            $form->hidden($campo,  \classes\Classes\crypt::encrypt_camp($_SESSION[$session]));
            return;
        }
        if(isset($array['block_notsession']) && $array['block_notsession'] == true) Redirect ($session);
        
        unset($array['especial']);
        if(isset($array['hide_notsession']) && $array['hide_notsession'] == true) return true;
        $this->LoadResource('formulario', 'form');
        $this->form->executeAction($campo, $array);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}