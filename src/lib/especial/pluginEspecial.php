<?php

class pluginEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $plugin = $array['plugin']['name'];
        $model = $array['plugin']['model'];
        $array['plugin']['name'] = $array['name'];
        
        $this->LoadJsPlugin($plugin, 'plugin');
        $this->plugin->addToForm($form, $campo, $array['plugin']);        
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>