<?php

class cnpjEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        if($valor == "") return true;
        if(!$this->validaCNPJ($valor)){
            $this->setErrorMessage("CNPJ inválido");
            return false;
        }
        return true;
    }
    
    public function js($campo, $array, $form){
        $validation = "cnpj: true";
        $message    = "cnpj: 'CNPJ Inválido'";
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addMask("$('#$campo').mask('99.999.999/9999-99');");
        //$this->jsval->addValidation($campo, $validation, $message);
        $form->text($campo, $array['name'],'', @$array['description']);
    }
    
    //-----------------------------------------------------
    //Funcao: validaCNPJ($cnpj)
    //Sinopse: Verifica se o valor passado é um CNPJ válido
    // Retorno: Booleano
    // Autor: Gabriel Fróes - www.codigofonte.com.br
    //-----------------------------------------------------
    private function validaCNPJ($cnpj) { 
        if (strlen($cnpj) != 18) return 0; 
        $soma1 = ($cnpj[0] * 5) + 

        ($cnpj[1] * 4) + 
        ($cnpj[3] * 3) + 
        ($cnpj[4] * 2) + 
        ($cnpj[5] * 9) + 
        ($cnpj[7] * 8) + 
        ($cnpj[8] * 7) + 
        ($cnpj[9] * 6) + 
        ($cnpj[11] * 5) + 
        ($cnpj[12] * 4) + 
        ($cnpj[13] * 3) + 
        ($cnpj[14] * 2); 
        $resto = $soma1 % 11; 
        $digito1 = $resto < 2 ? 0 : 11 - $resto; 
        $soma2 = ($cnpj[0] * 6) + 

        ($cnpj[1] * 5) + 
        ($cnpj[3] * 4) + 
        ($cnpj[4] * 3) + 
        ($cnpj[5] * 2) + 
        ($cnpj[7] * 9) + 
        ($cnpj[8] * 8) + 
        ($cnpj[9] * 7) + 
        ($cnpj[11] * 6) + 
        ($cnpj[12] * 5) + 
        ($cnpj[13] * 4) + 
        ($cnpj[14] * 3) + 
        ($cnpj[16] * 2); 
        $resto = $soma2 % 11; 
        $digito2 = $resto < 2 ? 0 : 11 - $resto; 
        return (($cnpj[16] == $digito1) && ($cnpj[17] == $digito2)); 
    } 	
	
	public function filter($name, $array){
		return $this->common_filter($name, $array, "Cnpj");
	}
			
	public function format($dados, &$value){
		$value = $this->mask($value, '##.###.###/####-##');
		return $value;
	}
}