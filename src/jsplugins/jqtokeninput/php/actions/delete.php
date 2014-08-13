<?php

require_once 'include.php';
$dados     = array($pk1 => $v1, $pk2 => $v2);
if(!$model_obj->delete($model, $dados)){
    $arr['status'] = 0;
    $arr['msg']    = $model_obj->getErrorMessage();
}else{
    $arr['status'] = 1;
    $arr['msg']    = $model_obj->getSuccessMessage();
}
echo json_encode($arr);

?>