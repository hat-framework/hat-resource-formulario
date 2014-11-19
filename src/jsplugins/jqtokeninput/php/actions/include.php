<?php
//$_POST['ajax'] = true;
$file = $_SERVER['DOCUMENT_ROOT'].'/init.php';
if(!file_exists($file)){
    $file = '../../../../../../init.php';
    if(!file_exists($file)){
        die(json_encode("{erro:'Não foi possível requisitar o arquivo de inicialização!', status:'0'}"));
    }
}
require_once $file;
require_once '../classes/jqtokeninputModel.php';
$model_obj = new jqtokeninputModel();
$model     = $_REQUEST['model'];
$pk1       = @$_REQUEST['pk1'];
$pk2       = @$_REQUEST['pk2'];
$v1        = @$_REQUEST['v1'];
$v2        = @$_REQUEST['v2'];

$tag       = @$_REQUEST['q'];
$limit     = @$_REQUEST['limit'];
$k1        = @$_GET['k1'];
$k2        = @$_GET['k2'];

$dados     = array($pk1 => $v1, $pk2 => $v2);