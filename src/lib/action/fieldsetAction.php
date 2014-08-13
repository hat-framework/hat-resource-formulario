<?php

use classes\Classes\Object;
class fieldsetAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        $form->fieldset($name, $type);
    }
    
    public function validar($name, $type, &$array){
        return true;
    }
    
    public function flush() {
        
    }
}

?>
