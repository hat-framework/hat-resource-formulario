<?php

require_once '../../../../init.php';
use classes\Classes\Object;
class setdata extends classes\Classes\Object{
    public function set(){
        $this->LoadModel("usuario/login", 'uobj');
        $this->LoadModel("galeria/album"  , 'aobj');
        
        $_REQUEST['usuario'] = $this->uobj->getCodUsuario();
        $_REQUEST['folder']  = "img/editor/";
        if(!isset($_SESSION['empty_album'])){
            $album = $this->aobj->getEmptyAlbum($_REQUEST['usuario']);
            $_REQUEST['album'] = $album['cod_album'];
        }else $_REQUEST['album'] = $_SESSION['empty_album'];
    }
}

$obj = new setdata();
$obj->set();

//print_r($_REQUEST);
require_once RESOURCES . "upload/lib/upload.php";

/*
if($_SERVER['REQUEST_METHOD']=='POST') { // Upload is complete
    if(empty($id) || !is_numeric($id)) {
        nicupload_error('Invalid Upload ID');
    }
    if(!is_dir(NICUPLOAD_PATH)){
        if(!mkdir(NICUPLOAD_PATH, 0700, true)){
            nicupload_error('O diretório '.NICUPLOAD_PATH.' não existe');
        }
    }
    if(!is_writable(NICUPLOAD_PATH)) {
        if(!chmod(NICUPLOAD_PATH, 0755)){
            nicupload_error('O diretório '.NICUPLOAD_PATH.' não tem permissão de escrita');
        }
    }

    $file = $_FILES['nicImage'];
    $image = $file['tmp_name'];

    $max_upload_size = ini_max_upload_size();
    if(!$file) {
        nicupload_error('Imagem deve conter no máximo'.bytes_to_readable($max_upload_size));
    }

    $ext = strtolower(substr(strrchr($file['name'], '.'), 1));
    @$size = getimagesize($image);
    if(!$size || !in_array($ext, $nicupload_allowed_extensions)) {
        nicupload_error('Invalid image file, must be a valid image less than '.bytes_to_readable($max_upload_size));
    }

    $filename = $id.'.'.$ext;
    $path = NICUPLOAD_PATH.'/'.$filename;

    if(!move_uploaded_file($image, $path)) {
        nicupload_error('Server error, failed to move file');
    }

    if($rfc1867) {
        $status = apc_fetch('upload_'.$id);
    }
    if(!$status) {
        $status = array();
    }
    $status['done'] = 1;
    $status['width'] = $size[0];
    $status['url'] = nicupload_file_uri($filename);

    if($rfc1867) {
        apc_store('upload_'.$id, $status);
    }

    nicupload_output($status, $rfc1867);
    exit;
}
*/
?>