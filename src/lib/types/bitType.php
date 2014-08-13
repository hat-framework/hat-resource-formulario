<?php

class bitType extends typeInterface{
    
    public $form_type = "";
    public function validate($campo, &$valor){
        $valor = is_array($valor)? array_shift($valor):$valor;
        $valor = ($valor == 1)?"FUNC_TRUE":"FUNC_FALSE";
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $selected = ($array['default'] == '0')     ?"0":"1";
        $value    = ($value == ""|| $value == '0') ?"0":"1";
        $this->form->hidden($name, '0'); //POG: É mais fácil sempre o item ser enviado do que somente as vezes
        $this->form->checkbox($name, '1', $caption, $selected, $desc); //É visualmente melhor exibir sim ou não como checkbox
        $this->form->addToForm("<br/>");
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
