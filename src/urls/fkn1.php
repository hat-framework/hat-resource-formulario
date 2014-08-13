<?php

require_once '../../../init.php';
use classes\Classes\Object;
class FormN1Key extends classes\Classes\Object{
    
    public function verifica(){
        
        try{
            if(array_key_exists("model", $_GET) && !empty ($_POST))
                return $this->execute($_GET['model']);

        }catch (Exception $e){
            return false;
        }
    }
    
    public function execute($model){
        
        if($_GET['action'] == "act"){
            $this->LoadModel($model, "mobj");
            $pkey = $this->mobj->getPkey();
            if(array_key_exists($pkey, $_POST)){
                $item = $this->mobj->getItem($_POST[$pkey]);
                if(empty ($item)) {
                    unset($_POST[$pkey]);
                    $bool = $this->mobj->inserir($_POST);
                }
                else $bool = $this->mobj->editar($_POST[$pkey], $_POST);
            }
            else $bool = $this->mobj->inserir($_POST);

            $this->setMessages($this->mobj->getMessages());
            return $bool;
        }
        else{
            $_SESSION['fkn1'][$model][] = serialize($_POST);
        }
        return true;
    }
    
}
$obj = new FormN1Key();
if($obj->verifica() === true){
    $out['status'] = "1";
    $out['success'] = "Dados Inseridos Corretamente";
}else{
    $out['status'] = "0";
    $out['erro'] = $obj->getErrorMessage();
}
echo json_encode($out);

?>
