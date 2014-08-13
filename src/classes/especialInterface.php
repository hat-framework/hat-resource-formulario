<?php

require_once 'block.php';
abstract class especialInterface extends block{
    
   /**
    * @abstract Valida via php os dados do campo
    * @return true, caso os dados sejam válido, false caso contrário
    */
    public abstract function validate($campo, &$valor);
    
    /**
    * @abstract Valida via php os dados do campo
    * @return true, caso os dados sejam válido, false caso contrário
    */
    public abstract function js($campo, $array, $form);
    
}