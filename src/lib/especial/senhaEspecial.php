<?php

class senhaEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $extra = "";
        if(isset($array['senha'])){
            $extra = " autocomplete='off'";
        }
        $form->password($campo, $array['name'], "", @$array['description'], $extra);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}