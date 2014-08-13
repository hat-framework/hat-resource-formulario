<?php

require_once 'include.php';
$k = "k1";
$i = 1;
$keys = array();
while(isset($_GET[$k])){
    $keys[] = $_GET[$k];
    $i++;
    $k = "k$i";
}
$var   = $model_obj->complete($model, $keys, $tag, $limit);
echo json_encode($var);