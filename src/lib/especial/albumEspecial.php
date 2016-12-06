<?php

class albumEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        if(!defined("UPLOADING")){
            $this->LoadModel("galeria/album", "galbum");
            if(!$this->galbum->setAlbumFull($valor)){
                $this->setErrorMessage($this->galbum->getErrorMessage());
                return false;
            }
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        $this->LoadModel("usuario/login", 'uobj');
        $usuario = $this->uobj->getCodUsuario();
        
        $campo = explode("[", $campo);
        $campo = array_shift($campo);
        $value = $form->getVar($campo);
        if($value == "") {
            $this->LoadModel("galeria/album", "galbum");
            $value = $this->galbum->getEmptyAlbum($usuario);
            $form->setVars($campo, $value);
        }
        $value = $value['cod_album'];
        $_SESSION['empty_album'] = $value;
        $this->LoadJsPlugin("upload/uploadify", "uify");
        $this->uify->configure($campo, $value, $usuario);
        $this->uify->draw($form, $campo, @$array['description']);
        
        //seta o campo no formulario
        $form->hidden($campo, $value);
        
    }	
	
	public function format($dados, &$value){
		
	}
	
	public function filter($name, $array){
		$value = "";
		return $value;
	}
}