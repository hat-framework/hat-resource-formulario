<?php    
   
    $array = array(
        //"Telefone"  => array("Telefone"),
        //"Login"     => array("TextCamp"),
        //"Senha"     => array("Senha", "Confirmar"),
        //"Email"     => array("email", "ConfirmText"),
        //"Cpf"       => array("mycpf"),
        //"Cpf"       => array("cpfs"),
        //"Data"      => array("mydate"),
        "mensagem"  => array("mensagem"),
        //"Cep"       => array("cep"),
        //"Cnpj"      => array('cnpj'),
        "captcha"      => array('captcha'),
    );
    $this->LoadComponent("Formulario/DrawForm", "dform");
    $this->dform->draw($array);
?>