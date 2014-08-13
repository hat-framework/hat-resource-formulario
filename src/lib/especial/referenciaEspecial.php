<?php

class referenciaEspecial extends especialInterface {
    
    public function validate($campo, &$valor){
        try{
            $val =  \classes\Classes\crypt::decrypt($valor);
            $this->LoadModel("app/referencia", 'ref');
            $valor = $this->ref->getRef($val);
        } catch (Exception $e){$valor = "";}
        return true;
    }
    
    public function js($campo, $array, $form){
        $value = (array_key_exists('default', $array))?$array['default']:"";
        $form->hidden($campo,  \classes\Classes\crypt::encrypt_camp($value));
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}

?>