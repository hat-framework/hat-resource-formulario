<?php

require_once 'include.php';
if(!$model_obj->add($model, $dados)){
    $arr['status'] = 0;
    $arr['msg']    = $model_obj->getErrorMessage();
}else{
    $arr['status'] = 1;
    $arr['msg']    = $model_obj->getSuccessMessage();
}
echo json_encode($arr);

?>