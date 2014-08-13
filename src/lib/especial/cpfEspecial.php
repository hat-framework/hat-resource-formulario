<?php

class cpfEspecial extends especialInterface{
    
    public function validate($campo, &$valor){
        $valor = str_replace(array(".", "-"), "", $valor);
        if($valor === ""){return true;}
        return $this->validaCPF($valor);
    }
    
    public function js($campo, $array, $form){
        $valor = $form->getVar($campo);
        if($valor != ""){
            $valor = substr($valor, 0, 3).".".substr($valor, 3, 3).".".substr($valor, 6, 3)."-".substr($valor, 9, 2);
            $form->setVars($campo, $valor);
        }
        
        $validation = "cpf:true";
        $message    = "cpf: 'CPF inválido, informe no formato 999.999.999-99'";
        
        $this->LoadJsPlugin("formulario/jqueryvalidate", 'jsval');
        $this->jsval->addMask("$('#$campo').mask('999.999.999-99');");
        $this->jsval->addValidation($campo, $validation, $message);
        $form->text($campo, $array['name'],'', @$array['description']);
    }
    
    private function validaCPF($cpf){
    
        // Verifiva se o número digitado contém todos os digitos
        $rep=preg_replace("/[^0-9]/", "", $cpf); 
        //$rep = ereg_replace('[^0-9]', '', $cpf);
        $cpf = str_pad($rep, 11, '0', STR_PAD_LEFT);
	
	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || 
             $cpf == '11111111111' || $cpf == '22222222222' || 
             $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || 
             $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999'){
	
            $this->setErrorMessage("CPF inválido");
            return false;
        }
        
	// Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf{$c} != $d) {
                $this->setErrorMessage("CPF inválido");
                return false;
            }
        }

        return true;
    }
    
    public function getSearchData(){
        die(__CLASS__);
    }
}