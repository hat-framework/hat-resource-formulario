<?php

require_once '../../../init.php';
use classes\Classes\Object;
class FormRecover extends classes\Classes\Object{
    
    private $campos = array();
    public function recover(){
        
        try{
            if(array_key_exists("model", $_GET)){
                $this->execute($_GET['model'], $_GET['pkey']);
                $this->result();
            }
        }catch (Exception $e){
            $this->result($e->getMessage());
        }
    }
    
    private function execute($model, $id){
        
        $this->LoadModel($model, "mobj");
        $campos = $this->mobj->getItem($id);
        foreach($campos as $name => $value){
            if(!is_array($value)){
                $this->campos[$name] = $value;
            }else{
                //$value = array_keys($value);
                //$value = array_shift($value);
                $this->campos[$name] = $value;
            }
        }
        //print_r($this->campos);
        
    }
    
    private function result($msg = ""){
        if(empty ($this->campos)){
            $arr['status'] = '0';
            $arr['erro']   = $msg;
        }else{
            $arr = $this->campos;
            $arr['status'] = '1';
        }
        echo json_encode($arr);
    }
    
}
$form = new FormRecover();
$form->recover();

?>

