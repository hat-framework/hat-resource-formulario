<?php

class textType extends typeInterface{
    
    public $form_type = "textarea";
    public function validate($campo, &$array){
        return true;
    }
    
    public function formulario($name, $array, $caption = "", $value = "", $desc = ""){
        $textlenth = (isset($array['textlenght'])? $array['textlenght']:"");
        $extra     = "";
        if($textlenth != ""){
            $cols = 80;
            $rows = ceil($textlenth/$cols);
            $extra = "cols='$cols' rows='$rows' style='resize: none;'";
        }
        $this->form->textarea($name, $caption, $value, $desc, $extra);
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>
