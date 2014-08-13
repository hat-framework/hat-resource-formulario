<?php
//$_POST['ajax'] = true;
require_once '../../../../../../init.php';
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
?>
