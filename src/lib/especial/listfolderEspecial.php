<?php

class listfolderEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        return true;
    }
    
    public function js($campo, $array, $form){
        $name = $array['name'];
        $list = $array['listfolder'];
        if(!array_key_exists("notnull", $array)){
            $out[''] = "Selecione uma Opção";
        }
        
        if(array_key_exists("replace", $array['listfolder'])){
            $replace = $array['listfolder']['replace'];
        }
        $replace[] = ".php";
        $diretorio = $list['folder'];
        $hide      = isset($list['hide'])?$list['hide']:array();
        //$hide[]    = ".DS_Store";
        
        //$out[] = array_key_exists("options", $array)?$array['options']:"";
        $this->LoadResource("files/dir", 'dir');
        $pastas = $this->dir->getPastas($diretorio);
        foreach($pastas as $pasta){
            if(!in_array($pasta, $hide))
                $out[str_replace ($replace, "", $pasta)] = ucfirst (str_replace ($replace, "", $pasta));
        }
        //$out = arsort($out);
        $default = array_key_exists('default', $array)?$array['default']:"";
        
        //seta o campo no formulario
        $form->select($campo, $out, $default, $name, @$array['description']);
        
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>