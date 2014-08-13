<?php

use classes\Classes\Object;
class UploadifyClass extends classes\Classes\Object{
    
    public function __construct() {
        $this->LoadJsPlugin('upload/uploadify', 'uify');
    }
    
    public function drawCamp($camp){
        $this->uify->drawForm($camp);
    }
    
    public function getAlbum($usuario){
        $this->LoadModel("galeria/album", 'aobj');
        if(isset($_SESSION['empty_album'])){return $_SESSION['empty_album'];}
       
        $arr = $this->aobj->getEmptyAlbum($usuario);
        return $arr['cod_album'];
    }
    
    public function getUsuario(){
        $this->LoadModel("usuario/login", 'uobj');
        return $this->uobj->getCodUsuario();
    }
    
    public function getUify(){
        return $this->uify;
    }
    
    public function flush(){
        $this->LoadResource("html", 'html');
        $this->html->flush();
    }
    
}

?>