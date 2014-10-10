<?php

use classes\Classes\Object;
class buttonAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $method = (isset($array['button_type']) && method_exists($form, $array['button_type']))?$array['button_type']:"submit";
        $id     = ($method == "submit")?"enviar":GetPlainName($name);
        $extra  = array();
        if(isset($array['button']) && is_array($array['button'])){
            foreach($type['attrs'] as $attr => $val){
                $extra[] = "$attr='$val'";
            }
            $type = $type['text'];
        }
        $extra = implode(" ", $extra);
        $form->$method($id, $type, $extra);
    }
    
    public function validar($name, $type, &$array){
        return true;
    }
    
    public function flush() {
        
    }
}