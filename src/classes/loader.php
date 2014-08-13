<?php

if(!defined('FORM_DIR')) define ("FORM_DIR", str_replace ("classes", "", dirname(__FILE__)));
function loadFormFile($file){
    $file = FORM_DIR . "$file";
    if(!file_exists($file)){
        throw new \Exception("Não foi possível carregar o arquivo ($file).<br/>");
    }
    require_once($file);
}

loadFormFile("classes/FormHelper.php");
loadFormFile("classes/JsValidator.php");
loadFormFile("classes/GenJsValidator.php");
loadFormFile("classes/typeInterface.php");
loadFormFile("classes/especialInterface.php");
loadFormFile("classes/actionInterface.php");
loadFormFile("validatorResource.php");

define("URL_FORM", URL . "recursos/formulario/lib/plugins/");

?>
