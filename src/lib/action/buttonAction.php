<?php

class buttonAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $extra  = array();
        $method = 'submit';
        $icon   = "";
        if(isset($array['button']) && is_array($array['button'])){
            $mtdname = isset($array['button']['button_type'])?$array['button']['button_type']:"submit";
            $method  = (method_exists($form, $mtdname))?$mtdname:"submit";
            $icon    = (isset($array['button']['icon']))?$array['button']['icon']:'';
            foreach($type['attrs'] as $attr => $val){
                $extra[] = "$attr='$val'";
            }
            $type = $type['text'];
        }
        $id    = ($method == "submit")?"enviar":GetPlainName($name);
        $ex    = implode(" ", $extra);
        $form->$method($id, $type, $ex, $icon);
    }
    
    public function validar($name, $type, &$array){
        return true;
    }
    
	public function filter($name, $array){}
	
    public function flush() {
        
    }
}