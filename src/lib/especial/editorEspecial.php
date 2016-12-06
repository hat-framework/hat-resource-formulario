<?php

class editorEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        if(MOBILE == false){
            $this->LoadResource("formulario/editor", 'editor');
            if(array_key_exists('editor', $array)) $this->editor->setOptions($array['editor']);
            $this->editor->execute($campo, $form);
        }
        $form->textarea($campo, ucfirst($array['name']), @$array['default'], @$array['description'], '', 'edit');
    }	
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Texto");
	}
			
	public function format($dados, &$value){
        loadFormFile("lib/action/types/textType.php");
		$textType = new textType();
		return $textType->format($dados, $value);
	}
}