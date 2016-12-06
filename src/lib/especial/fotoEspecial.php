<?php

class fotoEspecial extends especialInterface {
    
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
        $this->LoadModel("galeria/album", "galbum");
        $value = $this->galbum->getPublicAlbum();
        $form->setVars($campo, $value);
        $_SESSION['empty_album'] = $value;
        
        $this->LoadJsPlugin("upload/uploadify", "uify");
        $this->uify->configure($campo, $value, "", 'fotos', false);
        $this->uify->draw($form, $campo, @$array['description']);
        
        //seta o campo no formulario
        $form->hidden($campo, $value);
        
    }	
	
	public function filter($name, $array){
		return;
	}
	
	public function format($dados, &$value){
		return;
	}
}