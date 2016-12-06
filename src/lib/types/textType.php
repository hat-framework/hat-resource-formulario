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
	
	public function format($dados, &$value){
		if(!isset($dados['format']) || !isset($dados['format']['keeptags']) || $dados['format']['keeptags'] != true){
			$value = strip_tags($value, "<b><a><ul><li><ol><i><u>");
		}
		if(strlen($value) <= 120) {return;}
		$value = Resume($value, 120);
		return $value;
	}
	
	public function genQuery($name, $array, $params){
		echoBr(__METHOD__);
	}
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, 'Data');
	}
}