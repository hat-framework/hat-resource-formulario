<?php

use classes\Classes\Object;
class uniqueAction extends classes\Classes\Object implements actionInterface{
    
    public function executar($name, $type, $array, $form){
        
        $atual = $form->getVar($name);
        $atual = base64_encode($atual);
        $url   = \classes\Classes\Registered::getResourceLocationUrl('formulario');
        $model = $type['model'];
        $validation = "remote: {
            url: '$url/urls/unique.php?model=$model&atual=$atual',
            type: 'post',
            timeout: '4000',
            data: {
               $name: function() {
                return $('#$name').val();
              }
            }
        }";
        $message    = "remote: 'Item já registrado'";
        $this->LoadJsPlugin("formulario/jqueryvalidate", "jsval");
        
        $var = $form->getVar($name);
        if($var != "") $form->hidden($name."_old", $var);
        $this->jsval->addValidation($name, $validation, $message);
        
    }
    
    //responsabilidade de verificar se é unico passada para o sgbd
    public function validar($name, $type, &$valor){/*
        $where = "`$name` = '$valor'";
        if(isset($_POST[$name."_old"])){
            $old_value = $_POST[$name."_old"];
            $where .= " AND `$name` != '$old_value'";
        }
        $model = $type['model'];
        $this->LoadModel($model, "model_obj");
        
        $arr = $this->model_obj->selecionar(array($name), $where);
        if(empty ($arr)) return true;

        $this->setErrorMessage("O valor $valor já existe!");
        return false;*/
        return true;
    }
    
    public function flush() {
        
    }
}

?>
