<?php

class anexoEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        $val = armazem::getInstanceOf()->getVar($campo);
        //die($val);
        //getCodFolder($folder);
        return true;
    }
    
    public function js($campo, $array, $form){
        $this->LoadJsPlugin('upload/blueimp', 'up');
        $this->up->draw($form, $campo, $array['anexo'], @$array['description']);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>