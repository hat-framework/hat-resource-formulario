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
	
	/**
	 * @abstract Gera um ou mais campos para o formulário
	 * @return Retorna um array contendo campos para gerar um filtro para o tipo
	 */
	public abstract function filter($name, $array);
	
	/**
	 * @abstract Muda o valor de um campo para um formato legível para o usuário
	 * ex: 2014-03-05T00:00:00 => 05/03/2014 00:00:00
	 */
	public abstract function format($dados, &$value);
    
}