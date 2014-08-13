<?php

require_once 'block.php';
abstract class typeInterface extends block{
    
    public $form_type = "";
    protected $form;
    protected $data  = array();
    protected $name  = "";
    protected $model = null;
    
    public function __construct() {

    }
    
    public function setForm($form){
         $this->form = $form;
    }

   /**
    * @abstract Valida via php os dados do campo
    * @return true, caso os dados sejam válido, false caso contrário
    */
    public abstract function validate($campo, &$array);
    
    /**
    * @abstract Valida via php os dados do campo
    * @return true, caso os dados sejam válido, false caso contrário
    */
    public abstract function formulario($name, $array, $caption = "", $desc = "");

}

?>
