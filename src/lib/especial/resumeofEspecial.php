<?php

class resumeofEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        
        $arm = armazem::getInstanceOf();
        $array = $arm->getVar($campo);
        $post  = $arm->getVar('post');
        if(!isset($post[$array['resumeof']])){return true;}
        $valor = Resume($post[$array['resumeof']], $array['size']);
        return true;
    }
    
    public function js($campo, $array, $form){
        $value = (array_key_exists('default', $array))?$array['default']:"";
        $form->hidden($campo, $value);
        return;
    }	
	
	public function filter($name, $array){
		return;
	}
	
	public function format($dados, &$value){
		return;
	}
}