<?php

class urlEspecial extends especialInterface {
    
    public function validate($campo, &$url){
        if(trim($url) == ""){
            $url = trim($url);
            return true;
        }
        if(filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            $this->setErrorMessage("O campo $campo não é uma url válida!");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        $validation = "url: true";
        $message    = "url: 'Digite uma url válida!'";
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addValidation($campo, $validation, $message);
        if(!isset($array['default']) || $array['default'] == "") {
            $array['default'] = "http://";
        }
        $form->text($campo, $array['name'], @$array['default'], @$array['description']);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
