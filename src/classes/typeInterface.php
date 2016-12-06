<?php

require_once 'block.php';
abstract class typeInterface extends block{
    
    public $form_type = "";
    protected $form   = '';
    protected $data   = array();
    protected $name   = "";
    protected $model  = null;
    
    public function __construct() {

    }
    
    public function setForm($form){
         $this->form = $form;
    }
	
	public function getForm(){return $this->form;}

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

	/**
	 * @abstract Gera um ou mais campos para o formulário
	 * @return Retorna um array contendo campos para gerar um filtro para o tipo
	 */
	public abstract function filter($name, $array);
	
	/**
	 * @abstract Transforma os parâmetros enviados em um array 
	 * de restrições a ser utilizado pelo banco de dados
	 * @return Retorna um array de restrições
	 */
	public abstract function genQuery($name, $array, $params);
	
	/**
	 * @abstract Muda o valor de um campo para um formato legível para o usuário
	 * ex: 2014-03-05T00:00:00 => 05/03/2014 00:00:00
	 */
	public abstract function format($dados, &$value);
}