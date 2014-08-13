<?php

class varcharType extends typeInterface{
    
    public $form_type = "text";
    
    //qualquer coisa é varchar, não temos que validar
    public function validate($campo, &$array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        
        $size = isset($array['size'])?$array['size']:0;
        if($size >= 200){
            $nm = GetPlainName($name);
            $this->form->textarea($name, $caption, $value, $desc, "onkeyup=\"countChar(this, $size, 'v$nm');\" size =\"$size\"");
            
            $this->LoadResource('html', 'html');
            $this->html->LoadJs(URL.'/static/js/lib/formulario/countchar');
        }
        else {
            $extra = (isset($array['size']))?"maxlength='{$array['size']}'":"";
            $this->form->text($name, $caption, $value, $desc, $extra);        }
    }
    
    public function getSearchData(){
        //os tipos de texto não retornam nada nos formulários de busca,
        //pois haverá apenas 1 campo de texto para todos os tipos de texto.
        return array();
    }
}

?>
